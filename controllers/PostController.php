<?php

namespace app\controllers;

use app\models\Form\PostForm;
use app\services\PostService;
use app\filters\CreatePostLimitFilter;
use app\filters\DeletePostLimitFilter;
use app\filters\EditPostLimitFilter;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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

    public function behaviors(): array
    {
        return [
            'createPostLimit' => [
                'class' => CreatePostLimitFilter::class,
                'only' => ['create'],
                'postService' => $this->postService,
            ],
            'editPostLimit' => [
                'class' => EditPostLimitFilter::class,
                'only' => ['update', 'edit'],
            ],
            'deletePostLimit' => [
                'class' => DeletePostLimitFilter::class,
                'only' => ['delete'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(): string
    {
        $postForm = new PostForm();
        $posts = $this->postService->findAll();
        $ipAddresses = $this->postService->findUniqueIPs($posts);
        $ipCounts = $this->postService->findCountPostsByIp($ipAddresses);

        return $this->render('index', [
            'model' => $postForm,
            'posts' => $posts,
            'ipCounts' => $ipCounts,
        ]);
    }

    public function actionCreate(): Response
    {
        $postForm = new PostForm();

        if ($postForm->load(Yii::$app->request->post())
            && $postForm->validate()
            && $post = $this->postService->createPost($postForm)
        ) {
            $this->postService->sendEmailSuccess($post);

            return $this->redirect(['index']);
        }
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $postId): string
    {
        $post = $this->postService->findById($postId);

        $postForm = (new PostForm())
            ->setModeEdit()
            ->loadDataFromPost($post);

        return $this->render('edit', [
            'model' => $postForm,
        ]);
    }

    public function actionEdit(int $postId): Response|string
    {
        $postForm = (new PostForm())->setModeEdit();

        if ($postForm->load(Yii::$app->request->post())
            && $postForm->validate()
            && $this->postService->updatePost($postId, $postForm)
        ) {
            return $this->redirect(['update', 'postId' => $postId]);
        }

        return $this->render('edit', [
            'model' => $postForm,
        ]);
    }

    public function actionDelete(int $postId): Response
    {
        $this->postService->deletePost($postId);

        return $this->redirect(['index']);
    }
}