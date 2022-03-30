<table style ="width:100%">
    <thead>
        <tr>
            <th colspan="13" style="text-align:center; font-weight: bold; font-size: 14px;"> Daftar ICT Request (Sudah Dikerjakan)</th>
        </tr>
        <tr>
            <th colspan="13" style="text-align:center; font-weight: bold; font-size: 12px;"> {{date('d M Y')}}</th>
        </tr>
        <tr>
            <br>
        </tr>
    <tr>
        <th style="text-align:left; font-weight: bold;">No.</th>
        <th style="text-align:left; font-weight: bold;">No. Request</th>
        <th style="text-align:left; font-weight: bold;">No. Detail</th>
        <th style="text-align:left; font-weight: bold;">Nama Peripheral</th>
        <th style="text-align:left; font-weight: bold;">Tipe Request</th>
        <th style="text-align:left; font-weight: bold;">Tanggal Request</th>
        <th style="text-align:left; font-weight: bold;">Qty</th>
        <th style="text-align:left; font-weight: bold;">Deskripsi</th>
        <th style="text-align:left; font-weight: bold;">Keterangan</th>
        <th style="text-align:left; font-weight: bold;">Pemohon</th>
        <th style="text-align:left; font-weight: bold;">Pengguna</th>
        <th style="text-align:left; font-weight: bold;">Divisi Pengguna</th>
        <th style="text-align:left; font-weight: bold;">Bisnis Unit</th>
    </tr>
    </thead>
    <tbody>
    @php $no=1 @endphp
    @foreach($Ict as $i)
        <tr>
            <td style="text-align:left" width="5"  height="5">{{$no++}}</td>
            <td style="text-align:left" width="15" height="15">{{ $i->ireq_no }}</td>
            <td style="text-align:left" width="15" height="15">{{ $i->ireqd_id }}</td>
            <td style="text-align:left" width="15" height="15">{{ $i->name }}</td>
            <td style="text-align:left" width="15" height="15">{{ $i->ireq_date }}</td>
            <td style="text-align:left" width="20" height="15">{{ $i->ireq_type}}</td>
            <td style="text-align:left" width="18" height="10">{{ $i->ireq_qty }}</td>
            <td style="text-align:left" width="18" height="10">{{ $i->ireq_desc }}</td>
            <td style="text-align:left" width="18" height="10">{{ $i->ireq_remark }}</td>
            <td style="text-align:left" width="20" height="15">{{ $i->ireq_requestor}}</td>
            <td style="text-align:left" width="20" height="15">{{ $i->ireq_user}}</td>
            <td style="text-align:left" width="20" height="15">{{ $i->div_name}}</td>
            <td style="text-align:left" width="30" height="15">{{ $i->ireq_bu}}</td>
        </tr>
    @endforeach
    </tbody>
</table>