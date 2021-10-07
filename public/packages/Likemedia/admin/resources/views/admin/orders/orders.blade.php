@if(!$orders->isEmpty())
<div class="card">
    <div class="card-block">
        <table class="table table-hover table-striped" id="tablelistsorter">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{trans('variables.date_table')}}</th>
                    <th>{{trans('variables.email_text')}}</th>
                    <th>Delivery</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Payment</th>
                    <th>{{trans('variables.edit_table')}}</th>
                    <th>{{trans('variables.delete_table')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $key => $order)
                <tr id="{{ $order->id }}">
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{date("d.m.Y", strtotime($order->datetime))}}
                    </td>
                    <td>
                        @if (count($order->userLogged()->first()) > 0)
                          {{$order->userLogged()->first()->email}}
                        @else
                          {{$order->userUnlogged()->first()->email}}
                        @endif
                    </td>
                    <td>
                        {{trans('front.cabinet.historyOrder.'.$order->delivery)}}
                    </td>
                    <td>
                        {{trans('front.cabinet.historyOrder.'.$order->status)}}
                    </td>
                    <td>
                        {{$order->amount}}
                    </td>
                    <td>
                        {{trans('front.cabinet.historyOrder.'.$order->payment)}}
                    </td>
                    <td>
                        <a href="{{ route('order.edit', $order->id) }}">
                        <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td class="destroy-element">
                        <form action="{{ route('order.destroy', $order->id) }}" method="post">
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
