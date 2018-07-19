@extends('mail')
@section('content')

    <table class="body-wrap" bgcolor="#f6f6f6">
        <tr>
            <td valign="top"></td>
            <td class="container" width="600" valign="top">
                <div class="content">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0" bgcolor="#fff">
                        <tr>
                            <td class="alert alert-good" align="center" bgcolor="#1ab394" valign="top">
                                {{ trans('emails.internal.newUser.title') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="content-wrap" valign="top">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="content-block" valign="top" style="text-align: center">
                                            <p>{{ trans('emails.internal.newUser.msg1') }}</p>
                                            <p>{{ trans('emails.internal.newUser.msg2') }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block" valign="top" style="text-align: center">
                                            <a href="{{ url('users/'.$user['id']) }}" class="btn-primary">
                                                {{ trans('emails.internal.newUser.view_user') }}
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td valign="top"></td>
        </tr>
    </table>
@endsection