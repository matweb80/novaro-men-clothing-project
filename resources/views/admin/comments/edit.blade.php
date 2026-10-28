@component('admin.layouts.content',['title'=>'ویرایش نظر'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.comments.index') }}">لیست نظرات</a></li>
<li class="breadcrumb-item active">ویرایش نظر</li>
@endslot

@slot('script')
<script>
    document.querySelector('#approve').addEventListener('click', function(event) {
        var approve_label=document.querySelector('#approveLabel')
        if (this.checked==false) {
            approve_label.innerHTML="عدم تایید نظر"
        }else if(this.checked==true){
            approve_label.textContent="تایید نظر"
        }
    });
</script>
@endslot

<div class="row">
    <div class="col-lg-12">
        @include('admin.layouts.errors')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">فرم ویرایش نظر</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('admin.approved.comments.update',$comment->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">متن نظر</label>
                        <textarea name="comment" class="form-control" id="inputEmail3" placeholder="متن نظر را وارد کنید">{{ old('comment',$comment->comment) }}</textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="approve" id="approve" class="form-check-input" checked>
                        <label for="approve" class="form-check-label" id="approveLabel">تایید نظر</label>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">ویرایش نظر</button>
                    <a href="{{route('admin.comments.index')}}" class="btn btn-default float-left">لغو</a>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
    </div>
</div>

@endcomponent