<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Standard Quote',
                'subject' => 'Moving Quote for {customer_name}',
                'content' => '<p>Dear {customer_name},</p>
<p>Thank you for choosing our moving services. Below is your quote for the upcoming move:</p>
<p><strong>Move Details:</strong></p>
<ul>
    <li>Service Type: {service_type}</li>
    <li>Pickup Location: {pickup_location}</li>
    <li>Delivery Location: {delivery_location}</li>
    <li>Move Date: {move_date}</li>
    <li>Total Miles: {miles}</li>
</ul>
<p><strong>Pricing:</strong></p>
<ul>
    <li>Total Amount: ${total_amount}</li>
    <li>Down Payment: ${down_payment}</li>
    <li>Remaining Balance: ${remaining_balance}</li>
</ul>
<p>If you have any questions or would like to proceed with this quote, please don\'t hesitate to contact us.</p>
<p>Best regards,<br>{assigned_agent}</p>',
                'description' => 'Standard moving quote template with basic move details and pricing'
            ],
            [
                'name' => 'Quick Quote',
                'subject' => 'Quick Moving Quote - {customer_name}',
                'content' => '<p>Hi {customer_name},</p>
<p>Here\'s a quick quote for your upcoming move:</p>
<p>Total Amount: ${total_amount}</p>
<p>Move Date: {move_date}</p>
<p>From: {pickup_location}</p>
<p>To: {delivery_location}</p>
<p>Let me know if you\'d like to proceed!</p>
<p>Thanks,<br>{assigned_agent}</p>',
                'description' => 'Simple and quick quote template for basic moves'
            ],
            [
                'name' => 'Detailed Quote',
                'subject' => 'Detailed Moving Quote - {customer_name}',
                'content' => '<p>Dear {customer_name},</p>
<p>Thank you for considering our moving services. Please find your detailed quote below:</p>
<p><strong>Move Information:</strong></p>
<ul>
    <li>Service Type: {service_type}</li>
    <li>Pickup Location: {pickup_location}</li>
    <li>Delivery Location: {delivery_location}</li>
    <li>Move Date: {move_date}</li>
    <li>Total Miles: {miles}</li>
    <li>Lead Source: {lead_source}</li>
    <li>Transaction ID: {transaction_id}</li>
</ul>
<p><strong>Pricing Breakdown:</strong></p>
<ul>
    <li>Total Amount: ${total_amount}</li>
    <li>Down Payment: ${down_payment}</li>
    <li>Remaining Balance: ${remaining_balance}</li>
</ul>
<p><strong>Contact Information:</strong></p>
<ul>
    <li>Your Email: {customer_email}</li>
    <li>Your Phone: {customer_phone}</li>
    <li>Assigned Agent: {assigned_agent}</li>
</ul>
<p>Please review this quote carefully. If you have any questions or would like to make any changes, feel free to contact us.</p>
<p>Best regards,<br>{assigned_agent}</p>',
                'description' => 'Comprehensive quote template with detailed move information and pricing breakdown'
            ]
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
    }
} 