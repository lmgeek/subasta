@extends('admin')

@section('content')
<?php use Carbon\Carbon;?>
<br>
<script src="{{ asset('/js/plugins/sparkline/jquery.sparkline.min.js') }} "></script>


	<div class="ibox float-e-margins">

	<div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{{ trans('sellerBoats.listbatch') }}</h2>
        </div>
    </div>

	 @if (Session::has('confirm_msg_editbach'))
		 <br><br>
		<div class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{{ trans('sellerBoats.edit_batch') }}
		</div>
	 @endif

   <div class="wrapper wrapper-content">
		<div class="row">
		 <div class="ibox float-e-margins">
			 <div class="ibox-title">
                        <h5>{{ trans('sellerBoats.batch_info') }}</h5>
						<div class="ibox-tools">
							<a href="{{ url('sellerboat/arrive/0') }}" class="btn-action">
								<em class="fa fa-plus text-success"></em> {{ trans('sellerBoats.addbatch') }}
							</a>
                        </div>
                    </div>
			<div class="ibox-content">
			<div>
				<div class="feed-activity-list">
				@if(count($boats) == 0)
					{{ trans('boats.no_boats') }}
				@endif
				@foreach($boats as $boat)
					<?php $lastArrives = $boat->getLastArrive() ?>
					@foreach($lastArrives as $lastArrive)
						<div class="feed-element" style="border-bottom: 1px solid #2f4050">
							<a href="profile.html" class="pull-left">
								<!-- <img alt="image" class="img-circle" src="img/a4.jpg"> -->
							</a>
							<div class="media-body ">

								<div style="float:left;margin-right:10px;"><strong>{{ $boat->name }}</strong></div>
								<div><p class="text-navy text-muted "> {{ trans("boats.boats_date_arrive") }}
									@if (is_null($lastArrive))
										{{ trans('boats.boats_no_arrive') }}
									@endif
									@if ($lastArrive->date < date('Y-m-d H:i:s'))
										{{$lastArrive->date}}
									@else
										<em class="fa fa-clock-o"></em><a href="{{ route('sellerboat.editarrive',$lastArrive) }}" title="{{ trans('boats.edit_arrive') }}"> {{ Carbon::parse($lastArrive->date)->format('H:i:s d/m/Y') }} </a>
									@endif
								</p></div>

								@if ($lastArrive != null)
									@include("boat.partials.arrive_batch_status")
								@endif
								<div class="actions">
									@can('createBatch',$boat)
										@if(!is_null($lastArrive))
											<a href="{{ url('sellerboat/batch',$lastArrive) }}" class="btn btn-action"><em class="fa "></em>{{ trans('boats.boats_load_lote') }} </a>
										@endif
									@endcan
									<!-- <a class="btn btn-xs btn-white"><i class="fa "></i> Love</a> -->
								</div>
							</div>
						</div>
					 @endforeach
				 @endforeach

				 {!!  $boats->render() !!}
				</div>
				<!-- <button class="btn btn-primary btn-block m-t"><i class="fa fa-arrow-down"></i> Show More</button>-->
			</div>
		</div>
		</div>
		</div>
	</div>

		<style>
			 .amountBatch{
				font-weight: 600;
				font-family: 'Open Sans';
				font-size: 34px;
				margin-right: 0px;
				display: inline-block;
			}
		</style>
	</div>

@endsection