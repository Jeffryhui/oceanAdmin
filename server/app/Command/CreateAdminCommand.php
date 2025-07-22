<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Permission\SystemUser;
use HyperfExtension\Hashing\Hash;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\DbConnection\Db;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[Command]
class CreateAdminCommand extends HyperfCommand
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct('admin:create');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('创建管理员用户');
        $this->setHelp('此命令允许您交互式地创建一个新的管理员用户');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>欢迎使用管理员创建工具</info>');
        $output->writeln('<comment>请按提示输入管理员信息：</comment>');
        $output->writeln('');

        // 交互式输入用户名
        do {
            $username = $this->ask('请输入用户名');
            if (empty(trim($username))) {
                $output->writeln('<error>用户名不能为空</error>');
                continue;
            }
            if (SystemUser::where('username', $username)->exists()) {
                $output->writeln('<error>用户名已存在，请选择其他用户名</error>');
                continue;
            }
            break;
        } while (true);

        // 交互式输入昵称
        do {
            $nickname = $this->ask('请输入昵称');
            if (empty(trim($nickname))) {
                $output->writeln('<error>昵称不能为空</error>');
                continue;
            }
            break;
        } while (true);

        // 交互式输入密码
        do {
            $password = $this->ask('请输入密码', '');
            if (empty(trim($password))) {
                $output->writeln('<error>密码不能为空</error>');
                continue;
            }
            if (strlen($password) < 6) {
                $output->writeln('<error>密码长度不能少于6位</error>');
                continue;
            }
            
            $confirmPassword = $this->ask('请确认密码', '');
            if ($password !== $confirmPassword) {
                $output->writeln('<error>两次输入的密码不一致</error>');
                continue;
            }
            break;
        } while (true);

        // 交互式输入邮箱
        do {
            $email = $this->ask('请输入邮箱');
            if (empty(trim($email))) {
                $output->writeln('<error>邮箱不能为空</error>');
                continue;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $output->writeln('<error>邮箱格式不正确</error>');
                continue;
            }
            if (SystemUser::where('email', $email)->exists()) {
                $output->writeln('<error>邮箱已存在，请使用其他邮箱</error>');
                continue;
            }
            break;
        } while (true);

        // 交互式输入手机号
        do {
            $phone = $this->ask('请输入手机号');
            if (empty(trim($phone))) {
                $output->writeln('<error>手机号不能为空</error>');
                continue;
            }
            if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
                $output->writeln('<error>手机号格式不正确</error>');
                continue;
            }
            break;
        } while (true);

        // 交互式输入后台首页类型
        $dashboard = $this->ask('请输入后台首页类型', 'statistics');

        try {
            // 开始数据库事务
            Db::beginTransaction();

            // 创建管理员用户
            $admin = new SystemUser();
            $admin->username = $username;
            $admin->nickname = $nickname;
            $admin->password = Hash::make($password);
            $admin->email = $email;
            $admin->phone = $phone;
            $admin->dashboard = $dashboard;
            $admin->avatar = SystemUser::DEFAULT_AVATAR; // 使用默认头像
            $admin->status = SystemUser::STATUS_NORMAL; // 正常状态
            $admin->save();

            Db::commit();

            $output->writeln('');
            $output->writeln('<info>✓ 管理员用户创建成功！</info>');
            $output->writeln('');
            $output->writeln('<comment>用户信息：</comment>');
            $output->writeln("用户ID: {$admin->id}");
            $output->writeln("用户名: {$admin->username}");
            $output->writeln("昵称: {$admin->nickname}");
            $output->writeln("邮箱: {$admin->email}");
            $output->writeln("手机号: {$admin->phone}");
            $output->writeln("后台首页: {$admin->dashboard}");
            $output->writeln("头像: {$admin->avatar}");
            $output->writeln("状态: " . ($admin->status == SystemUser::STATUS_NORMAL ? '正常' : '禁用'));
            $output->writeln('');

            return self::SUCCESS;
        } catch (\Exception $e) {
            Db::rollBack();
            $output->writeln('<error>创建管理员用户失败: ' . $e->getMessage() . '</error>');
            return self::FAILURE;
        }
    }
} 