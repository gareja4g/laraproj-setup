@php
    $page = Request::get('page');
    $currentPage = Request::get('page');
    $sn = 0;
    if ($page > 1) {
        $sn = $perPage * ($currentPage - 1);
    }
@endphp
<table class="table">
    <tr>
        <th>No.</th>
        <th>@sortablelink('name', 'Name')</th>
        <th>@sortablelink('email', 'Email')</th>
        <th>Phone</th>
        <th>Gender</th>
        <th>Image</th>
        <th>File</th>
        <th class="text-right">Action</th>
    </tr>
    <tbody>
        @if (isset($result['result']) && $result['result']->count())
            @foreach ($result['result'] as $key => $user)
                <tr>
                    <td>{{ $key + 1 + $sn }}</td>
                    <td>{{ $user->name ?? '' }}</td>
                    <td>{{ $user->email ?? '' }}</td>
                    <td>{{ $user->phone ?? '' }}</td>
                    <td>{{ $user->gender ?? '' }}</td>
                    <td>
                        @if ($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="User Image" width="50"
                                height="50" />
                        @else
                            <span>No image</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->file)
                            <a href="{{ asset('storage/' . $user->file) }}"
                                download="{{ basename($user->file) }}">Download</a>
                        @else
                            <span>No file</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <span onclick="ajaxScript(this);" url="{{ route('edit', $user->id) }}" method="get"
                            class="cursor-pointer">
                            <i class="bi bi-pen" data-toggle="tooltip" data-placement="top" title="Update"></i>
                        </span>
                        <span onclick="delete_records(this);" url="{{ route('delete', $user->id) }}" method="delete"
                            class="cursor-pointer">
                            <i class="bi bi-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i>
                        </span>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" class="text-center">Oops! No Record Found</td>
            </tr>
        @endif
    </tbody>
</table>
@if (isset($result['paginate']))
    <div class="row mr-1 mt-3 float-right links" pagination-container="usr-data">{!! $result['paginate']->appends($requestData)->links('pagination::bootstrap-5') !!}</div>
@endif
