<div v-if="showModal == 'stationSetting'" v-cloak>
    <div class="card-header">
        <h5>最寄駅を選択</h5>
    </div>
    <div class="card-body">
        <h6>駅名から探す</h6>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-8">
                <input v-model="keyword" @keydown.enter="getSearchStations" class="form-control mt-3" placeholder="駅名を入力"  value="va" />
            </div>
            <div class="col-12 col-lg-4">
                <button type="button" @click="getSearchStations" class="btn btn-primary my-3" :disabled="!enabledSearch">駅を検索</button>
            </div>
        </div>
        <div class="col-md-12" v-if="message">
            <p is="message" :message="message"></p>
        </div>
        <div class="col-md-12 my-3" v-if="searchStations.length">
            <ul is="SearchStations" v-for="(station, index) in searchStations"
                :railroad="station.railroad"
                :name="station.name"
                @add="add(station)"
            ></ul>
        </div>
        <h6 class="mt-3">路線から探す</h6>
        <hr>
        @php
            $beforeCompanyId = "";
        @endphp
        <ul class="list-group">
        @foreach($railroads as $key => $railroad)
            @if($beforeCompanyId != $railroad->railroad_company_id)
                <li class="list-group-item list-group-item-success text-center">{{ $railroad->company->name }}</li>
            @endif
                <li class="list-group-item">
                    <a style="cursor: pointer;" @click="showStations"  data-railroad="{{ json_encode($railroad) }}" data-key="{{ $key }}" v-if="checkeStationModal({{ $key }})">{{ $railroad->name }}🔼</a>
                    <a style="cursor: pointer;" @click="showStations"  data-railroad="{{ json_encode($railroad) }}" data-key="{{ $key }}" v-else>{{ $railroad->name }}🔽</a>
                </li>
                <div v-show="checkeStationModal({{ $key }})" class="list-group">
                    @foreach($railroad->stations as $station)
                        <a class="list-group-item list-group-item-primary" style="cursor: pointer;" @click="choiseAdd" data-station="{{ json_encode($station) }}">{{ $station->name }}駅</a>
                    @endforeach
                </div>
            @php
                $beforeCompanyId = $railroad->railroad_company_id;
            @endphp
        @endforeach
        </ul>
        <div class="col-md-12 text-right">
            <button type="button" class="btn btn-secondary my-3" @click="closeModal">閉じる</button>
        </div>
    </div>
</div>
