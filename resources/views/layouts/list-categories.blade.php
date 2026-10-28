<ul class="list-group list-group-flush">
    @foreach($categories as $category)
    <li class="list-group-item">
        <div class="d-flex">
            <span>
                {{ $category->name }}
            </span>
            @canany(['delete-category','edit-category','submit-category-child'])
            <div class="actions mr-2">
                <form action="{{ route('admin.categories.destroy',$category->slug) }}" id="cate-{{ $category->id }}-delete" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
                <a href="#" onclick="event.preventDefault(); document.querySelector('#cate-{{ $category->id }}-delete').submit()" class="badge badge-danger">حذف</a>
                <a href="{{route('admin.categories.edit',$category->slug)}}" class="badge badge-primary">ویرایش</a>
                <a href="{{route('admin.categories.create.child',$category->slug)}}" class="badge badge-warning">ثبت زیر دسته</a>
            </div>
            @endcan
        </div>
        @if($category->child)
        @include('layouts.list-categories' , ['categories' => $category->child])
        @endif
    </li>
    @endforeach
</ul>