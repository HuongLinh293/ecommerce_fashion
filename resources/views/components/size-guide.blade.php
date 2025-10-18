<div id="sizeGuideModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
  <div class="bg-white p-6 rounded-lg w-96 max-h-[90vh] overflow-y-auto">
    <h2 class="text-xl font-bold mb-4">Size Guide ({{ $product->type ?? '' }})</h2>

    @if(!empty($sizeGuide))
      <table class="w-full border-collapse border">
        <thead>
          <tr class="border-b bg-gray-100">
            @foreach(array_keys($sizeGuide[0]) as $key)
              <th class="p-2 text-left capitalize">{{ str_replace('_', ' ', $key) }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($sizeGuide as $row)
            <tr class="border-b">
              @foreach($row as $value)
                <td class="p-2">{{ $value }}</td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>No size guide available for this product.</p>
    @endif

    <button onclick="document.getElementById('sizeGuideModal').classList.add('hidden')"
            class="mt-4 px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
      Close
    </button>
  </div>
</div>
