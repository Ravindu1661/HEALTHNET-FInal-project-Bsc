@extends('laboratory.layouts.app')
@section('title','Lab Tests')
@section('page-title','Lab Tests')
@section('page-subtitle','Manage your laboratory tests')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-3">
        <form method="GET" class="flex gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tests..."
                    class="pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none w-64">
            </div>
            <select name="category" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category')==$cat ? 'selected':'' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                <option value="">All Status</option>
                <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>Inactive</option>
            </select>
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition">Filter</button>
        </form>
    </div>
    <a href="{{ route('laboratory.tests.create') }}"
       class="bg-teal-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-teal-700 transition flex items-center gap-2 shadow-sm">
        <i class="fas fa-plus"></i> Add New Test
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Test Name</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Category</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Price</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Duration</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($tests as $test)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-900">{{ $test->test_name }}</p>
                        @if($test->description)
                        <p class="text-xs text-gray-400 truncate max-w-xs">{{ $test->description }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($test->test_category)
                        <span class="bg-teal-50 text-teal-700 px-2.5 py-1 rounded-full text-xs font-medium">{{ $test->test_category }}</span>
                        @else
                        <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 font-semibold text-gray-900">Rs. {{ number_format($test->price, 2) }}</td>
                    <td class="px-5 py-4 text-gray-600">
                        {{ $test->duration_hours ? $test->duration_hours.' hrs' : '—' }}
                    </td>
                    <td class="px-5 py-4">
                        <button onclick="toggleTest({{ $test->id }}, this)"
                            data-active="{{ $test->is_active ? '1':'0' }}"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors
                            {{ $test->is_active ? 'bg-teal-500' : 'bg-gray-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                {{ $test->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                        <span class="ml-2 text-xs {{ $test->is_active ? 'text-teal-600':'text-gray-400' }}">
                            {{ $test->is_active ? 'Active':'Inactive' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('laboratory.tests.edit', $test) }}"
                               class="p-2 text-gray-500 hover:text-teal-700 hover:bg-teal-50 rounded-lg transition text-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('laboratory.tests.destroy', $test) }}"
                                  onsubmit="return confirm('Delete this test?')">
                                @csrf @method('DELETE')
                                <button class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-16 text-center">
                    <i class="fas fa-vials text-gray-200 text-5xl mb-3 block"></i>
                    <p class="text-gray-400 font-medium">No tests added yet</p>
                    <a href="{{ route('laboratory.tests.create') }}" class="mt-3 inline-block text-teal-600 text-sm hover:underline">Add your first test →</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tests->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $tests->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleTest(id, btn) {
    fetch(`/laboratory/tests/${id}/toggle`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
    })
    .then(r => r.json())
    .then(data => {
        const active = data.status === 'active';
        btn.dataset.active = active ? '1' : '0';
        btn.className = `relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${active ? 'bg-teal-500' : 'bg-gray-300'}`;
        btn.querySelector('span').className = `inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform ${active ? 'translate-x-6' : 'translate-x-1'}`;
        btn.nextElementSibling.textContent = active ? 'Active' : 'Inactive';
        btn.nextElementSibling.className = `ml-2 text-xs ${active ? 'text-teal-600' : 'text-gray-400'}`;
    });
}
</script>
@endpush
