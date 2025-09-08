@extends('client.dashboard.layouts.app')

@section('content')
    <div class="col-lg-9">
        <div class="dashboard">
            <div class="box-head">
                <h3>Địa chỉ đã lưu</h3>
                <button class="btn btn-solid-default btn-sm fw-bold ms-auto" data-bs-toggle="modal"
                    data-bs-target="#addAddress"><i class="fas fa-plus"></i>
                    Thêm địa chỉ mới</button>
            </div>
            <div class="save-details-box">
                <div class="row g-3">
                    @foreach ($addresses as $item)
                        <div class="col-xl-4 col-md-6">
                            <div class="save-details">
                                <div class="save-name">
                                    <h5>Tên: {{ $item->full_name }}</h5>
                                    <div class='save-position'>
                                        <h6>{{ $item->nickname }}</h6>
                                    </div>
                                </div>

                                <div class="save-address">
                                    <p class="font-light">Địa chỉ: {{ $item->address }}</p>
                                </div>

                                <div class="mobile">
                                    <p class="font-light mobile">Sđt: {{ $item->phone }}</p>
                                </div>

                                <div class="button">
                                    <a type='button' data-addressid="{{ $item->id }}"
                                        data-url="{{ route('client.address.update', ['id' => $item->id]) }}"
                                        class="btn btn-sm edit-address">Sửa</a>

                                    <a href="javascript:void(0)" class="btn btn-sm delete-address"
                                        data-url="{{ route('client.address.destroy', $item->id) }}">Xóa</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Add Address Modal Start -->
    <div class="modal fade add-address-modal" id="addAddress">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('client.address.create') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="full_name" class="form-label font-light">Họ và tên</label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" required>
                            @error('full_name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label font-light">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" required>
                            @error('phone')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nickname" class="form-label font-light">Tên địa chỉ</label>
                            <input type="text" class="form-control @error('nickname') is-invalid @enderror"
                                id="nickname" name="nickname" required>
                            @error('nickname')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label font-light">Địa chỉ</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                required></textarea>
                            @error('address')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default"
                                value="0">
                            <label class="form-check-label font-light" for="is_default">Đặt làm mặc định ?</label>
                        </div>

                    </div>

                    <div class="modal-footer pt-0 text-end d-block">
                        <button type="button" class="btn bg-secondary text-white rounded-1"
                            data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-solid-default rounded-1">Xong</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Address Modal End -->

    <div class="modal fade add-address-modal" id="editAddress">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="editAddressForm" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label font-light">Họ và tên</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-light">Số điện thoại</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-light">Tên địa chỉ</label>
                            <input type="text" class="form-control" name="nickname" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-light">Địa chỉ</label>
                            <textarea class="form-control" name="address" rows="2" required></textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_default" id="edit_is_default">
                            <label class="form-check-label font-light" for="edit_is_default">Đặt làm mặc định ?</label>
                        </div>
                    </div>

                    <div class="modal-footer pt-0 text-end d-block">
                        <button type="button" class="btn bg-secondary text-white rounded-1"
                            data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-solid-default rounded-1">Xong</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.edit-address').on('click', function() {
                const addressId = $(this).data('addressid');
                const url = $(this).data('url');

                if (!addressId) {
                    console.error('addressId undefined');
                    return;
                }

                $.get(`/client/api/address/${addressId}/edit`, function(response) {
                    if (!response.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: response.message
                        });
                        return;
                    }

                    let data = response.data;
                    let modal = $('#editAddress');

                    modal.find('input[name="full_name"]').val(data.full_name);
                    modal.find('input[name="phone"]').val(data.phone);
                    modal.find('input[name="nickname"]').val(data.nickname);
                    modal.find('textarea[name="address"]').val(data.address);
                    modal.find('input[name="is_default"]').prop('checked', data.is_default == 1);

                    $('#editAddressForm').attr('action', url);

                    modal.modal('show');
                });
            });

            $('.delete-address').on('click', function(e) {
                e.preventDefault();
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Bạn có chắc muốn xóa địa chỉ này?',
                    text: "Hành động này không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $('<form>', {
                            'method': 'POST',
                            'action': url
                        });
                        let token = $('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        });
                        let method = $('<input>', {
                            'type': 'hidden',
                            'name': '_method',
                            'value': 'DELETE'
                        });
                        form.append(token, method);
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
