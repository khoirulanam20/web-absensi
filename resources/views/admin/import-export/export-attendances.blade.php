<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Date</th>
      <th>Name</th>
      <th>NIP</th>
      <th>Time In</th>
      <th>Time Out</th>
      <th>Shift</th>
      <th>Barcode Id</th>
      <th>Coordinates</th>
      <th>Status</th>
      <th>Lokasi (WFH/WFO)</th>
      <th>Notes</th>
      <th>Note</th>
      <th>Attachment</th>
      <th>Created At</th>
      <th>Updated At</th>

      <th>User Id</th>
      <th>Shift Id</th>
      <th>Raw Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($attendances as $attendance)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $attendance->date?->format('Y-m-d') }}</td>
        <td>{{ $attendance->user?->name }}</td>
        <td data-type="s">{{ $attendance->user?->nip }}</td>
        <td>{{ $attendance->time_in?->format('H:i:s') }}</td>
        <td>{{ $attendance->time_out?->format('H:i:s') }}</td>
        <td>{{ $attendance->shift?->name }}</td>
        <td>{{ $attendance->barcode_id }}</td>
        <td data-type="s">
          {{ $attendance->lat_lng ? $attendance->latitude . ',' . $attendance->longitude : null }}
        </td>
        <td>
          @php
            $statusLabels = [
              'hadir' => 'Hadir',
              'izin' => 'Izin',
              'sakit' => 'Sakit',
              'cuti' => 'Cuti',
              'present' => 'Hadir',
              'late' => 'Terlambat',
              'excused' => 'Izin',
              'sick' => 'Sakit',
              'absent' => 'Tidak Hadir'
            ];
            $statusLabel = $statusLabels[$attendance->status] ?? ucfirst($attendance->status);
          @endphp
          {{ $statusLabel }}
        </td>
        <td>{{ $attendance->is_wfh ? 'WFH (Rumah)' : ($attendance->status === 'hadir' || $attendance->status === 'present' ? 'WFO (Kantor)' : '-') }}</td>
        <td>{{ $attendance->notes }}</td>
        <td>{{ $attendance->note }}</td>
        <td>{{ $attendance->attachment }}</td>
        <td>{{ $attendance->created_at }}</td>
        <td>{{ $attendance->updated_at }}</td>

        <td>{{ $attendance->user_id }}</td>
        <td>{{ $attendance->shift_id }}</td>
        <td>{{ $attendance->status }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
