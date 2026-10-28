@component('admin.layouts.content',['title'=>'تنظیمات'])

@slot('breadcrumb')
<li class="breadcrumb-item"><a href="/admin">پنل مدیریت</a></li>
<li class="breadcrumb-item active">تنظیمات</li>
@endslot

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-bold mb-4">تنظیم رنگ سایت 🎨</h2>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="p-2 bg-green-100 text-green-700 rounded mb-3">
                    {{ session('success') }}
                </div>
                @endif
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @php
                    $colorFields= [
                    'primary_color'=> 'رنگ اصلی سایت',
                    'secondary_color'=> 'رنگ ثانویه',
                    'accent_color'=> 'رنگ تاکیدی (دکمه ها و هایلایت)',
                    'danger_color'=> 'رنگ هشدار',
                    'shadow_color'=> 'رنگ سایه',
                    ]
                    @endphp

                    @foreach($colorFields as $key => $label)
                    <div class="mb-5">
                        <label class="block mb-2 font-semibold">{{ $label }}:</label>
                        <input type="color" name="{{$key}}" value="{{ $colorSettings[$key] ?? '#000000' }}" class="w-24 h-10 border rounded">

                        <button type="submit" class="btn btn-light mr-4 px-4 py-2 rounded border">
                            ذخیره رنگ
                        </button>
                    </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>

@endcomponent