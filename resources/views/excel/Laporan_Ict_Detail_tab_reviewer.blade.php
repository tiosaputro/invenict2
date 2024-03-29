<table style ="width:100%">
    <thead>
        <tr>
            <th colspan="7" style="text-align:center; font-weight: bold; font-size: 14px;"> ICT Request Detail</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align:center; font-weight: bold; font-size: 12px;"> {{date('d M Y')}}</th>
        </tr>
        <tr>
            <br>
        </tr>
        <tr>
            <th colspan="2" style="text-align:left; font-weight: bold; font-size: 12px;"> No. request</th>
            <th colspan="1" style="text-align:left; font-weight: bold; font-size: 12px;"> {{ $detail[0]->ireq_no }} </th>
            <th colspan="1" style="text-align:left; font-weight: bold; font-size: 12px;"> Requestor</th>
            <th colspan="2" style="text-align:left; font-weight: bold; font-size: 12px;"> {{$detail[0]->ireq_requestor}}</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align:left; font-weight: bold; font-size: 12px;"> Tgl. Request</th>
            <th colspan="1" style="text-align:left; font-weight: bold; font-size: 12px;"> {{ formatDate($detail[0]->ireq_date, 'd M y') }}</th>
            <th colspan="1" style="text-align:left; font-weight: bold; font-size: 12px;"> Bisnis Unit</th>
            <th colspan="2" style="text-align:left; font-weight: bold;font-size: 12px;"> {{$detail[0]->ireq_bu}}</th>
        </tr>
        <tr>
            <th colspan="2" style="text-align:left; font-weight: bold; font-size: 12px;"> Status</th>
            <th colspan="1" style="text-align:left; font-weight: bold; font-size: 12px;"> {{$detail[0]->ireqq_status}}</th>
        </tr>
        <tr>
            <br>
        </tr>
    <tr>
        <th style="text-align:left; font-weight: bold;">No.</th>
        <th style="text-align:left; font-weight: bold;">Tipe Request</th>
        <th style="text-align:left; font-weight: bold;">Nama Peripheral</th>
        <th style="text-align:left; font-weight: bold;">Deskripsi</th>
        <th style="text-align:left; font-weight: bold;">Qty</th>
        <th style="text-align:left; font-weight: bold;">Keterangan</th>
        <th style="text-align:left; font-weight: bold;">Status</th>
    </tr>   
    </thead>
    <tbody>
    @php $no=1 @endphp
    @foreach($detail as $d)
        <tr>
            <td style="text-align:left;" width="5"  height="5">{{$no++}}</td>
            <td style="text-align:left;" width="15" height="15">{{ $d->ireq_type }}</td>
            <td style="text-align:left;" width="40" height="10">{{ $d->name }}</td>
            <td style="text-align:left;" width="20" height="15">{{ $d->ireq_desc}}</td>
            <td style="text-align:left;" width="20" height="15">{{ $d->ireq_qty}}</td>
            <td style="text-align:left;" width="20" height="15">{{ $d->ireq_remark}}</td>
            <td style="text-align:left;" width="20" height="15">{{ $d->ireq_status}}</td>
        </tr>
    @endforeach
    </tbody>
</table>