<div class="modal fade" id="showModal{{ $customer->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <div class="text-center mb-3">
                    @if ($customer->profile_image)
                        <img src="{{ asset('storage/' . $customer->profile_image) }}" class="rounded-circle" width="100" height="100">
                    @else
                        <p>No image</p>
                    @endif
                </div>
                <p><strong>Name:</strong> {{ $customer->name }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($customer->gender) }}</p>
                <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                <p><strong>Address:</strong> {{ $customer->address }}</p>
                <p><strong>Created At:</strong> {{ $customer->created_at->format('d-m-Y') }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
