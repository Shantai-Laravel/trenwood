@if(!$returns->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{trans('front.admin.active')}}</th>
                    <th>{{trans('variables.date_table')}}</th>
                    <th>{{trans('variables.email_text')}}</th>
                    <th>{{trans('front.general.delivery')}}</th>
                    <th>{{trans('front.cabinet.historyAll.status')}}</th>
                    <th>{{trans('front.cart.price')}}</th>
                    <th>{{trans('front.admin.payment')}}</th>
                    <th>{{trans('variables.edit_table')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returns as $key => $return)
                <tr id="{{ $return->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $return->is_active == 0 ? 'no' : 'yes' }}
                    </td>
                    <td>
                        {{date("d.m.Y", strtotime($return->datetime))}}
                    </td>
                    <td>
                        @if (count($return->userLogged()->first()) > 0)
                          {{$return->userLogged()->first()->email}}
                        @else
                          {{$return->userUnlogged()->first()->email}}
                        @endif
                    </td>
                    <td>
                      {{trans('front.cabinet.historyAll.deliveryMethod', ['delivery' => $return->delivery])}}
                    </td>
                    <td>
                      {{trans('front.cabinet.status.'.$return->status)}}
                    </td>
                    <td>
                        {{$return->amount}}
                    </td>
                    <td>
                      {{trans('front.cabinet.historyAll.paymentMethod', ['payment' => $return->payment])}}
                    </td>
                    <td>
                        <a href="{{ route('returns.edit', $return->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('returns.destroy', $return->id) }}" method="post">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <button type="submit" class="btn-link">
                                <a href=""><i class="fa fa-trash"></i></a>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=7></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@else
<div class="empty-response">{{trans('variables.list_is_empty')}}</div>
@endif
