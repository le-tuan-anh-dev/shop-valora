@extends('admin.layouts.main_nav')

@section('content')
               <div class="page-content">

                    <!-- Start Container Fluid -->
                    <div class="container-xxl">

                         @if (session('success'))
                             <div class="row">
                                 <div class="col-12">
                                     <div class="alert alert-success alert-dismissible fade show" role="alert">
                                         <iconify-icon icon="solar:check-circle-bold-duotone" class="me-2"></iconify-icon>
                                         {{ session('success') }}
                                         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                     </div>
                                 </div>
                             </div>
                         @endif

                         @if (session('error'))
                             <div class="row">
                                 <div class="col-12">
                                     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                         <iconify-icon icon="solar:close-circle-bold-duotone" class="me-2"></iconify-icon>
                                         {{ session('error') }}
                                         <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                     </div>
                                 </div>
                             </div>
                         @endif

                         <div class="row">
                              <div class="col-md-6 col-xl-3">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-2">Chưa thanh toán</h4>
                                                       <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($stats['unpaid_orders']) }}</p>
                                                  </div>
                                                  <div>
                                                       <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                                            <iconify-icon icon="solar:chat-round-money-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-6 col-xl-3">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-2">Đã hủy</h4>
                                                       <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($stats['cancelled_orders']) }}</p>
                                                  </div>
                                                  <div>
                                                       <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                                            <iconify-icon icon="solar:cart-cross-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="col-md-6 col-xl-3">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-2">Chờ xử lý</h4>
                                                       <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($stats['pending_orders']) }}</p>
                                                  </div>
                                                  <div>
                                                       <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                                            <iconify-icon icon="solar:tram-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="col-md-6 col-xl-3">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-2">Đã xác nhận</h4>
                                                       <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($stats['confirmed_orders']) }}</p>
                                                  </div>
                                                  <div>
                                                       <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                                            <iconify-icon icon="solar:clipboard-remove-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="col-md-6 col-xl-3">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-2">Đang giao</h4>
                                                       <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($stats['shipping_orders']) }}</p>
                                                  </div>
                                                  <div>
                                                       <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                                            <iconify-icon icon="solar:box-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-6 col-xl-3">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-2">Tổng đơn hàng</h4>
                                                       <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($stats['total_orders']) }}</p>
                                                  </div>
                                                  <div>
                                                       <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                                            <iconify-icon icon="solar:clock-circle-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="col-md-6 col-xl-3">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="d-flex align-items-center justify-content-between">
                                                  <div>
                                                       <h4 class="card-title mb-2">Hoàn thành</h4>
                                                       <p class="text-muted fw-medium fs-22 mb-0">{{ number_format($stats['completed_orders']) }}</p>
                                                  </div>
                                                  <div>
                                                       <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                                            <iconify-icon icon="solar:clipboard-check-broken" class="fs-32 text-primary avatar-title"></iconify-icon>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="row">
                              <div class="col-xl-12">
                                   <div class="card">
                                        <div class="d-flex card-header justify-content-between align-items-center">
                                             <div>
                                                  <h4 class="card-title">Danh sách đơn hàng</h4>
                                             </div>
                                             <div>
                                                  <form method="GET" action="{{ route('admin.orders.list') }}" class="d-flex gap-2">
                                                       <input type="text" 
                                                              name="search" 
                                                              class="form-control form-control-sm" 
                                                              placeholder="Tìm kiếm..." 
                                                              value="{{ request('search') }}"
                                                              style="width: 200px;">
                                                       <select name="status" class="form-select form-select-sm" style="width: 150px;">
                                                            <option value="">Tất cả trạng thái</option>
                                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                                            <option value="awaiting_pickup" {{ request('status') == 'awaiting_pickup' ? 'selected' : '' }}>Chờ lấy hàng</option>
                                                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                                            <option value="cancelled_by_customer" {{ request('status') == 'cancelled_by_customer' ? 'selected' : '' }}>Khách hủy</option>
                                                            <option value="cancelled_by_admin" {{ request('status') == 'cancelled_by_admin' ? 'selected' : '' }}>Admin hủy</option>
                                                            <option value="delivery_failed" {{ request('status') == 'delivery_failed' ? 'selected' : '' }}>Giao thất bại</option>
                                                       </select>
                                                       <button type="submit" class="btn btn-sm btn-primary">
                                                            <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                                                       </button>
                                                  </form>
                                             </div>
                                        </div>
                                        <div class="card-body p-0">
                                             <div class="table-responsive">
                                                  <table class="table align-middle mb-0 table-hover table-centered">
                                                       <thead class="bg-light-subtle">
                                                            <tr>
                                                                 <th>Mã đơn hàng</th>
                                                                 <th>Ngày đặt</th>
                                                                 <th>Khách hàng</th>
                                                                 <th>Tổng tiền</th>
                                                                 <th>Thanh toán</th>
                                                                 <th>Số lượng</th>
                                                                 <th>Trạng thái</th>
                                                                 <th>Hành động</th>
                                                            </tr>
                                                       </thead>
                                                       <tbody>
                                                            @forelse($orders as $order)
                                                            <tr>
                                                                 <td>
                                                                      <span class="fw-semibold">#{{ $order->order_number }}</span>
                                                                 </td>
                                                                 <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                                 <td>
                                                                      <a href="#" class="link-primary fw-medium">{{ $order->customer_name }}</a>
                                                                      <p class="text-muted mb-0 fs-12">{{ $order->customer_email }}</p>
                                                                 </td>
                                                                 <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                                                 <td>
                                                                      @php
                                                                          $paymentStatusColors = [
                                                                              'unpaid' => ['bg' => 'bg-light', 'text' => 'text-dark', 'label' => 'Chưa thanh toán'],
                                                                              'paid' => ['bg' => 'bg-success', 'text' => 'text-light', 'label' => 'Đã thanh toán'],
                                                                              'failed' => ['bg' => 'bg-danger', 'text' => 'text-light', 'label' => 'Thanh toán thất bại']
                                                                          ];
                                                                          $paymentStatus = $paymentStatusColors[$order->payment_status] ?? ['bg' => 'bg-light', 'text' => 'text-dark', 'label' => ucfirst($order->payment_status)];
                                                                      @endphp
                                                                      <span class="badge {{ $paymentStatus['bg'] }} {{ $paymentStatus['text'] }} px-2 py-1 fs-13">{{ $paymentStatus['label'] }}</span>
                                                                 </td>
                                                                 <td>{{ $order->orderItems->count() }}</td>
                                                                 <td>
                                                                      @php
                                                                          $statusColors = [
                                                                              'pending' => ['border' => 'border-warning', 'text' => 'text-warning', 'label' => 'Chờ xác nhận'],
                                                                              'confirmed' => ['border' => 'border-info', 'text' => 'text-info', 'label' => 'Đã xác nhận'],
                                                                              'awaiting_pickup' => ['border' => 'border-info', 'text' => 'text-info', 'label' => 'Chờ lấy hàng'],
                                                                              'shipping' => ['border' => 'border-primary', 'text' => 'text-primary', 'label' => 'Đang giao'],
                                                                              'delivered' => ['border' => 'border-success', 'text' => 'text-success', 'label' => 'Đã giao hàng'],
                                                                              'completed' => ['border' => 'border-success', 'text' => 'text-success', 'label' => 'Đã hoàn thành'],
                                                                              'cancelled_by_customer' => ['border' => 'border-danger', 'text' => 'text-danger', 'label' => 'Khách hủy'],
                                                                              'cancelled_by_admin' => ['border' => 'border-danger', 'text' => 'text-danger', 'label' => 'Admin hủy'],
                                                                              'delivery_failed' => ['border' => 'border-danger', 'text' => 'text-danger', 'label' => 'Giao thất bại']
                                                                          ];
                                                                          $status = $statusColors[$order->status] ?? ['border' => 'border-secondary', 'text' => 'text-secondary', 'label' => ucfirst($order->status)];
                                                                      @endphp
                                                                      <span class="badge border {{ $status['border'] }} {{ $status['text'] }} px-2 py-1 fs-13">{{ $status['label'] }}</span>
                                                                 </td>
                                                                 <td>
                                                                      <div class="d-flex gap-2">
                                                                           <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-title="Xem chi tiết">
                                                                                <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                                           </a>
                                                                           <div class="dropdown">
                                                                                <button class="btn btn-soft-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-toggle="tooltip" data-bs-title="Cập nhật trạng thái">
                                                                                     <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                                                </button>
                                                                                <ul class="dropdown-menu">
                                                                                     @php
                                                                                         $statusOptions = [
                                                                                             'pending' => 'Chờ xác nhận',
                                                                                             'confirmed' => 'Đã xác nhận',
                                                                                             'awaiting_pickup' => 'Chờ lấy hàng',
                                                                                             'shipping' => 'Đang giao',
                                                                                             'delivered' => 'Đã giao hàng',
                                                                                             'completed' => 'Đã hoàn thành',
                                                                                             'cancelled_by_customer' => 'Khách hủy',
                                                                                             'cancelled_by_admin' => 'Admin hủy',
                                                                                             'delivery_failed' => 'Giao thất bại',
                                                                                         ];
                                                                                         $allowedStatuses = $order->allowedStatuses ?? [];
                                                                                         $isCancelled = in_array($order->status, ['cancelled_by_customer', 'cancelled_by_admin', 'completed']);
                                                                                     @endphp
                                                                                     
                                                                                     @foreach($statusOptions as $statusKey => $statusLabel)
                                                                                         @php
                                                                                             $isAllowed = in_array($statusKey, $allowedStatuses);
                                                                                             $isCurrent = $order->status == $statusKey;
                                                                                             $isCancelStatus = in_array($statusKey, ['cancelled_by_customer', 'cancelled_by_admin', 'delivery_failed']);
                                                                                         @endphp
                                                                                         @if($isAllowed || $isCurrent)
                                                                                             @if($isCancelStatus && !$isCurrent && $loop->first)
                                                                                                 <li><hr class="dropdown-divider"></li>
                                                                                             @endif
                                                                                             <li>
                                                                                                 <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                                                                                     @csrf
                                                                                                     @method('PUT')
                                                                                                     <input type="hidden" name="status" value="{{ $statusKey }}">
                                                                                                     <button type="submit" 
                                                                                                             class="dropdown-item {{ $isCurrent ? 'active' : '' }} {{ $isCancelStatus ? 'text-danger' : '' }}"
                                                                                                             {{ $isCurrent ? 'disabled' : '' }}>
                                                                                                         {{ $statusLabel }}
                                                                                                         @if($isCurrent)
                                                                                                             <span class="float-end">✓</span>
                                                                                                         @endif
                                                                                                     </button>
                                                                                                 </form>
                                                                                             </li>
                                                                                         @endif
                                                                                     @endforeach
                                                                                     
                                                                                     @if(empty($allowedStatuses) && !$isCancelled)
                                                                                         <li class="dropdown-item-text text-muted small">Không có trạng thái nào có thể chuyển</li>
                                                                                     @endif
                                                                                </ul>
                                                                      </div>
                                                                      </div>
                                                                 </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                 <td colspan="8" class="text-center py-5">
                                                                      <div class="mb-3">
                                                                           <iconify-icon icon="solar:inbox-bold-duotone" style="font-size: 3rem; opacity: 0.5;"></iconify-icon>
                                                                      </div>
                                                                      <h5 class="text-muted">Không có đơn hàng nào</h5>
                                                                 </td>
                                                            </tr>
                                                            @endforelse
                                                       </tbody>
                                                  </table>
                                             </div>
                                             <!-- end table-responsive -->
                                        </div>
                                        @if($orders->hasPages())
                                        <div class="card-footer border-top">
                                             <nav aria-label="Page navigation example">
                                                  {{ $orders->links('pagination::bootstrap-5') }}
                                             </nav>
                                        </div>
                                        @endif
                                   </div>
                              </div>

                         </div>

                    </div>
                    <!-- End Container Fluid -->

                    <!-- ========== Footer Start ========== -->
                    <footer class="footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <script>document.write(new Date().getFullYear())</script> &copy; Larkon. Crafted by <iconify-icon icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                                        href="https://1.envato.market/techzaa" class="fw-bold footer-text" target="_blank">Techzaa</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- ========== Footer End ========== -->

               </div>
@endsection