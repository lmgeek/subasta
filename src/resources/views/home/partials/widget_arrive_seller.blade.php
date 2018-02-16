<?php use Carbon\Carbon;?>
<br>
<script src="{{ asset('/js/plugins/sparkline/jquery.sparkline.min.js') }} "></script>
<div class="col-lg-8">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5>{{ trans('boats.boats_arrive') }}</h5>
			<div class="ibox-tools">
				<!-- <span class="label label-warning-light">10 Messages</span> -->
			   </div>
		</div>
		<div class="ibox-content">
			<div>
				<div class="feed-activity-list">
				@if(count($boats) == 0)
					{{ trans('boats.no_boats') }}
				@endif
				@foreach($boats as $boat)
					<?php $lastArrive = $boat->getLastArrive() ?>
					<div class="feed-element">
						<a href="profile.html" class="pull-left">
							<!-- <img alt="image" class="img-circle" src="img/a4.jpg"> -->
						</a>
						<div class="media-body ">
							
							<div style="float:left;margin-right:10px;"><strong>{{ $boat->name }}</strong></div>
							<div><p class="text-navy text-muted "> {{ trans("boats.boats_next_arrive") }}
								@if (is_null($lastArrive))
									{{ trans('boats.boats_no_arrive') }}
								@else
									<i class="fa fa-clock-o"></i> {{ Carbon::parse($lastArrive->date)->format('H:i:s d/m/Y') }}
								@endif
							</p></div>
							
							@if ($lastArrive != null)
								@include("home.partials.arrive_batch_status")
							
							@endif
							<div class="actions">
								<a href="{{ url('sellerboat/arrive',$boat) }}" class="btn btn-xs btn-white"><i class="fa "></i>{{ trans('boats.boats_new_arrive') }} </a>
								@if(!is_null($lastArrive))
									<a href="{{ url('sellerboat/batch',$lastArrive) }}" class="btn btn-xs btn-white"><i class="fa "></i>{{ trans('boats.boats_load_lote') }} </a>
								@endif
								<!-- <a class="btn btn-xs btn-white"><i class="fa "></i> Love</a> -->
							</div>
						</div>
					</div>
				 @endforeach
				</div>
				<!-- <button class="btn btn-primary btn-block m-t"><i class="fa fa-arrow-down"></i> Show More</button>-->
			</div>
		</div>
	</div>
</div>