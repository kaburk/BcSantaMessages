<?php
declare(strict_types=1);

use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->plugin(
        'BcSantaMessage',
        ['path' => '/bc-santa-message'],
        function (RouteBuilder $builder): void {
            // フロント画面
            $builder->connect('/', ['controller' => 'SantaMessages', 'action' => 'index']);

            // API（JSON）
            $builder->connect('/api/generate', ['controller' => 'SantaMessages', 'action' => 'generate'])
                ->setMethods(['POST']);

            // 管理画面
            $builder->prefix('Admin', function (RouteBuilder $admin): void {
                $admin->connect('/santa-message', ['controller' => 'SantaMessages', 'action' => 'index']);

                // テスト生成（管理画面用）
                $admin->connect('/santa-message/test-generate', [
                    'controller' => 'SantaMessages',
                    'action' => 'testGenerate'
                ])->setMethods(['POST']);
            });
        }
    );
};
