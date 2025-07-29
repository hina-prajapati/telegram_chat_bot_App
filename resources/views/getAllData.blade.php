<h2>All Users</h2>
<table border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>City</th>
            <th>Gender</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $record)
            <tr>
                <td>{{ $record->name }}</td>
                <td>{{ $record->city }}</td>
                <td>{{ $record->gender }}</td>
                <td>
                    <a href="{{ route('profiles.show', ['id' => $record->id]) }}">View Profile</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
