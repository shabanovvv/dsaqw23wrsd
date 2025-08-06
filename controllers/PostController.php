<?php

namespace app\controllers;

use app\models\Form\PostForm;
use app\services\PostService;
use Yii;
use yii\base\Module;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Response;

class PostController extends Controller
{
    public function __construct(
        string $id,
        Module $module,
        public PostService $postService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws Exception
     */
    public function actionIndex(): Response|string
    {
        $postForm = new PostForm();
        if ($postForm->load(Yii::$app->request->post()) && $postForm->validate()) {
            if ($this->postService->save($postForm)) {
                Yii::$app->session->setFlash('success', 'Данные успешно сохранены!');
                return $this->redirect(['index']);
            }
        } else {
            // Получаем ошибки валидации
            $errors = $postForm->getErrors();

            // Можно записать их в сессию для отображения на следующем этапе
            Yii::$app->session->setFlash('error', $errors);

            // ИЛИ выводим ошибки напрямую
            foreach ($errors as $error) {
                foreach ($error as $message) {
                    echo $message . '<br>'; // Выводим ошибки
                }
            }
        }

        return $this->render('index', [
            'model' => $postForm,
        ]);
    }
}