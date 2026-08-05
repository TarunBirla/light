<h2>New Equipment Request ({{ $lead['product_type'] ?? 'Rental Request' }})</h2>

<p><strong>Request Type:</strong> <span style="font-weight: bold; color: {{ ($lead['product_type'] ?? '') == 'Selling Request' ? '#166534' : '#1d4ed8' }};">{{ $lead['product_type'] ?? 'Rental Request' }}</span></p>

<p><strong>Name:</strong> {{ $lead['name'] }}</p>

<p><strong>Email:</strong> {{ $lead['email'] }}</p>

<p><strong>Phone:</strong> {{ $lead['phone'] }}</p>

<p><strong>Message:</strong> {{ $lead['message'] }}</p>

<p><strong>Requested Items:</strong></p>

<ul>
    @foreach($lead['items'] as $item)
        <li>{{ $item }}</li>
    @endforeach
</ul>