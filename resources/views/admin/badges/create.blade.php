@component('admin.layouts.content',['title'=>'ایجاد نشان محصول'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.badges.index') }}"> نشان‌های محصولات</a></li>
<li class="breadcrumb-item active">ایجاد نشان</li>
@endslot

<div class="row">
    <div class="col-lg-12">

        @include('admin.layouts.errors')

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    ایجاد نشان محصول
                </h3>
            </div>

            <form
                action="{{ route('admin.badges.store') }}"
                method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label>
                            نام نشان
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="مثلاً پرفروش">
                    </div>

                    <div class="form-group">
                        <label>
                            Slug
                        </label>

                        <input
                            type="text"
                            name="slug"
                            class="form-control"
                            dir="ltr"
                            value="{{ old('slug') }}"
                            placeholder="bestseller">
                    </div>

                    <div class="form-group">
                        <label>
                            رنگ
                        </label>

                        <input
                            type="color"
                            name="color"
                            value="{{ old('color', '#C8A96A') }}"
                            class="form-control"
                            style="height: 45px;">
                    </div>

                    <div class="form-group">
                        <label>
                            اولویت
                        </label>

                        <input
                            type="number"
                            name="priority"
                            class="form-control"
                            value="{{ old('priority', 0) }}"
                            min="0">

                        <small class="form-text text-muted">
                            عدد بزرگ‌تر = اولویت بیشتر
                        </small>
                    </div>

                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="is_active"
                            value="1"
                            class="form-check-input"
                            id="is_active"
                            checked>

                        <label
                            class="form-check-label"
                            for="is_active">
                            فعال باشد
                        </label>
                    </div>

                </div>

                <div class="card-footer">
                    <button
                        type="submit"
                        class="btn btn-info">
                        ثبت نشان
                    </button>

                    <a
                        href="{{ route('admin.badges.index') }}"
                        class="btn btn-default float-left">
                        لغو
                    </a>
                </div>

            </form>

        </div>

    </div>
</div>

@endcomponent