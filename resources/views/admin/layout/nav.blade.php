<aside class="col-md-4 blog-sidebar bg-dark text-white">
    <section class="p-4 mb-3 sticky-top h-100" >
        <ul class="nav flex-column">
            <li class="nav-item ">
                <a class="nav-link text-light" href="{{route('posts.index')}}">Главная</a>
            </li>
            <li class="nav-item {{ Request::is('admin/posts') ? 'bg-light rounded' : '' }}">
                <a class="nav-link text-{{ Request::is('admin/posts') ? 'dark' : 'light' }}" href="{{route('admin.posts.index')}}">Cтатьи</a>
            </li>
            <li class="nav-item {{ Request::is('admin/feedbacks') ? 'bg-light rounded' : '' }}">
                <a class="nav-link text-{{ Request::is('admin/feedbacks') ? 'dark' : 'light' }}" href="{{route('admin.feedbacks.index')}}">Отзывы</a>
            </li>
        </ul>
    </section>
</aside>


