@extends('admin.layouts.main_nav')

@section('content')
<div class="page-content">
    <div class="container-xxl">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Danh sách thuộc tính</h4>
            <a href="{{ route('admin.attributes.add') }}" class="btn btn-primary">
                + Thêm thuộc tính
            </a>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="card-title">Danh sách thuộc tính & giá trị</h4>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0 table-hover table-centered">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên thuộc tính</th>
                                    <th>Slug</th>
                                    <th>Giá trị </th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attributes as $attribute)
                                    <tr>
                                        <td>{{ $attribute->id }}</td>
                                        <td>{{ $attribute->name }}</td>
                                        <td>{{ $attribute->slug }}</td>

                                        {{-- Hiển thị danh sách giá trị của thuộc tính --}}
<td>
    @if($attribute->values->count() > 0)
        {{ $attribute->values->pluck('value')->join(' - ') }}
    @else
        <span class="text-muted">—</span>
    @endif
</td>


                                        <td>{{ $attribute->created_at ? $attribute->created_at->format('d/m/Y') : '—' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="btn btn-soft-primary btn-sm">
                                                    <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                                <form action="{{ route('admin.attributes.delete', $attribute->id) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc muốn xoá thuộc tính này không?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-soft-danger btn-sm">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Chưa có thuộc tính nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Phân trang --}}
                    @if (method_exists($attributes, 'links'))
                        <div class="card-footer border-top">
                            {{ $attributes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
