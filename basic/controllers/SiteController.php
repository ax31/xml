<?php

namespace app\controllers;

use app\models\File;
use app\models\Tag;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    protected $tags = [];

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new File();
        $queryCount = "SELECT COUNT(*) FROM file LEFT JOIN 
        (SELECT file_id, SUM(number) as sum_number FROM tag GROUP BY file_id) t ON t.file_id = file.id WHERE sum_number > 20";
        $countFiles = 0;

        try {
            $countFiles = Yii::$app->db->createCommand($queryCount)->queryScalar();
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $model->xml = UploadedFile::getInstance($model, 'xml');
            $model->title = $model->xml->name;
            libxml_use_internal_errors(true);
            $xml = simplexml_load_file($model->xml->tempName);

            if (!$xml) {
                $xmlError = "Ошибка загрузки XML<br>";

                foreach (libxml_get_errors() as $error) {
                    $xmlError .= $error->message . "<br>";
                }

                Yii::$app->session->setFlash('error', $xmlError);
            } else {
                $this->tags[$xml->getName()] = 1;
                $this->getTagsCount($xml);
                $model->save();

                foreach ($this->tags as $tagName => $number) {
                    $modelTag = new Tag();
                    $modelTag->file_id = $model->primaryKey;
                    $modelTag->tagname = $tagName;
                    $modelTag->number = $number;
                    $modelTag->save();
                }

                $model->loadDefaultValues(false);
            }
        }

        $files = File::find()->all();

        return $this->render('index', [
            'model' => $model,
            'files' => $files,
            'countFiles' => $countFiles
        ]);
    }

    public function actionFile($id)
    {
        $model = File::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Файл не найден', 404);
        }

        return $this->render('file', [
            'model' => $model
        ]);
    }

    protected function getTagsCount($node)
    {
        foreach ($node as $xmlTag) {
            if (isset($this->tags[$xmlTag->getName()])) {
                $this->tags[$xmlTag->getName()] += 1;
            } else {
                $this->tags[$xmlTag->getName()] = 1;
            }

            if ($xmlTag->children()) {
                $this->getTagsCount($xmlTag);
            }
        }
    }

}
