@extends('mail')


@section('content')
<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table  cellpadding="0" width="100%" cellspacing="0">
                                <tr>
                                    <td>
										<div style="text-align:center;background-color:#1ab394;color:#FFF;font-size:18px;font-weight:bold;padding:10px;">{{ trans('users.change_password')  }}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <h3>{{ $user['name'] ." " . $user['lastname']  }}</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
									<br>
                                     <a href="{{ url('password/reset/'.$token) }}"> {{ trans('users.password_link') }} </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="content-block aligncenter">
                                        
                                    </td>
                                </tr>
                              </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                   
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
@endsection






