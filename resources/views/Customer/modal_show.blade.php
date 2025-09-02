<div class="modal fade" id="showModal{{ $customer->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details - ID: {{ $customer->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <p><strong>ID:</strong> {{ $customer->id }}</p>
                <p><strong>Full Name:</strong> {{ $customer->name }}</p>
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Phone:</strong> {{ $customer->phone ?? '-' }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($customer->gender) }}</p>
                <p><strong>Created At:</strong> {{ $customer->created_at->format('d-m-Y H:i') }}</p>

                @if ($customer->profile_image)
                    <p><strong>Profile Image:</strong></p>
                    <img src="{{ asset('storage/' . $customer->profile_image) }}" alt="Profile Image" class="img-fluid rounded" style="max-width: 100%;">
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
