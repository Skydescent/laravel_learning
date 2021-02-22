<?php

namespace App\Console\Commands;

use App\Post;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class NotifyAboutNewPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify_about_new_posts {days : Количество дней за которые нужно собрать новые статьи}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Рассылает всем пользователям сообщение о новых статьях за период, указанный в аргументах';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->arguments();
        $users = User::all();
        $posts = Post::where('published', 1)
            ->whereDate('created_at', '>', now()->subDays($this->argument('days')))
            ->get();
        foreach ($users as $user) {
            $user->notify(new \App\Notifications\NotifyAboutNewPostsCommandGiven( $posts, $this->argument('days')));
        }

    }
}
