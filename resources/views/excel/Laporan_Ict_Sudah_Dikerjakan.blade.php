<table style ="width:100%">
    <thead>
        <tr>
            <th colspan="13" style="text-align:center; font-weight: bold; font-size: 14px;"> ICT REQUEST STATUS REPORT LIST ON {{date('d M Y')}}</th>
        </tr>
        <tr>
            <br>
        </tr>
    <tr>
        <th style="text-align:left; font-weight: bold;">No. Request</th>
        <th style="text-align:left; font-weight: bold;">No. Detail</th>
        <th style="text-align:left; font-weight: bold;">Request Type</th>
        <th style="text-align:left; font-weight: bold;">Peripheral</th>
        <th style="text-align:left; font-weight: bold;">Qty</th>
        <th style="text-align:left; font-weight: bold;">Remark</th>
        <th style="text-align:left; font-weight: bold;">Request Date</th>
        <th style="text-align:left; font-weight: bold;">Requestor</th>
        <th style="text-align:left; font-weight: bold;">User</th>
        <th style="text-align:left; font-weight: bold;">User Division</th>
        <th style="text-align:left; font-weight: bold;">Business Unit</th>
        <th style="text-align:left; font-weight: bold;">ICT Personnel</th>
        <th style="text-align:left; font-weight: bold;">Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($Ict as $i)
        <tr>
            <td style="text-align:left" width="15" height="15">{{ $i->ireq_no }}</td>
            <td style="text-align:left" width="10" height="15">{{ $i->ireqd_id }}</td>
            <td style="text-align:left" width="15" height="15">{{ $i->ireq_type}}</td>
            <td style="text-align:left" width="17" height="15">{{ $i->name }}</td>
            <td style="text-align:left" width="10" height="10">{{ $i->ireq_qty }}</td>
            <td style="text-align:left" width="18" height="10">{{ $i->ireq_remark }}</td>
            <td style="text-align:left" width="15" height="15">{{ $i->ireq_date }}</td>
            <td style="text-align:left" width="20" height="15">{{ $i->ireq_requestor}}</td>
            <td style="text-align:left" width="20" height="15">{{ $i->ireq_user}}</td>
            <td style="text-align:left" width="20" height="15">{{ $i->div_name}}</td>
            <td style="text-align:left" width="30" height="15">{{ $i->ireq_bu}}</td>
            <td style="text-align:left" width="30" height="15">{{ $i->ireq_assigned_to}}</td>
            <td style="text-align:left" width="30" height="15">{{ $i->ireq_status}}</td>
        </tr>
    @endforeach
    </tbody>
</table>