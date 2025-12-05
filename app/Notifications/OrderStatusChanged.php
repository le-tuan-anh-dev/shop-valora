<?php

namespace App\Notifications;

use App\Models\Admin\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    // Không implements ShouldQueue để tránh tạo job vào bảng `jobs`
    use Queueable;

    public function __construct(public Order $order, public ?string $note = null)
    {
    }

    /**
     * Chỉ lưu notification vào database (không gửi mail/không queue).
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Dữ liệu sẽ lưu vào bảng notifications->data
     */
    public function toArray($notifiable)
    {
        [$statusLabel, $defaultMessage] = $this->statusInfo();

        return [
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'status'       => $this->order->status,
            'status_label' => $statusLabel,
            'message'      => $this->note ?? $defaultMessage,
        ];
    }

    /**
     * Trả về nhãn và thông điệp mặc định tương ứng với từng status.
     */
    private function statusInfo(): array
    {
        $statusLabels = [
            'pending'               => 'Đang chờ',
            'confirmed'             => 'Đã xác nhận',
            'awaiting_pickup'       => 'Chờ lấy hàng',
            'shipping'              => 'Đang giao',
            'delivered'             => 'Đã giao',
            'completed'             => 'Hoàn thành',
            'cancelled_by_customer' => 'Khách đã hủy',
            'cancelled_by_admin'    => 'Shop đã hủy',
            'delivery_failed'       => 'Giao hàng thất bại',
        ];

        $templates = [
            'pending'               => 'Đơn hàng đã được tạo và đang chờ shop xác nhận.',
            'confirmed'             => 'Shop đã xác nhận đơn hàng. Chúng tôi sẽ tiến hành đóng gói.',
            'awaiting_pickup'       => 'Đơn hàng đã sẵn sàng và chờ đơn vị vận chuyển tới lấy.',
            'shipping'              => 'Đơn hàng của bạn đang trong quá trình vận chuyển.',
            'delivered'             => 'Đơn hàng đã được giao tới địa chỉ bạn cung cấp.',
            'completed'             => 'Đơn hàng đã hoàn tất. Cảm ơn bạn đã mua sắm.',
            'cancelled_by_customer' => 'Bạn đã hủy đơn hàng này.',
            'cancelled_by_admin'    => 'Shop đã hủy đơn hàng. Vui lòng kiểm tra chi tiết.',
            'delivery_failed'       => 'Giao hàng không thành công. Shop sẽ liên hệ hỗ trợ bạn.',
        ];

        $status = $this->order->status;
        $label = $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
        $message = $templates[$status] ?? "Đơn hàng {$this->order->order_number} hiện ở trạng thái {$label}.";

        return [$label, $message];
    }
}