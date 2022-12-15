import axios from 'axios'
import Vue from 'vue'
// Vue.component('Select', require('./components/Select.vue'))
// 会社に紐づいた駅のテンプレート
Vue.component('CompanyStations', require('./components/CompanyStations.vue'))
// 検索結果の駅のテンプレート
Vue.component('SearchStations', require('./components/SearchStations.vue'))
// 通信結果メッセージのテンプレート
Vue.component('message', require('./components/message.vue'))

function getJsonList(idName) {
    var json = document.getElementById(idName).dataset.list
    return JSON.parse(json) || []
}

function getList(keys) {
    var list = []
    keys.forEach(function(key) {
        list[key] = getJsonList(key)
    })
    return list
}

function index(obj, val) {
    return  Object.keys(obj).filter( (key) => { 
      return obj[key] === val
    });
}

const URL_BASE = "/api/stations"
var list = getList(["railroads", "companyStations"])

var stationVue = new Vue({
    // #CompanyStationsVue以下のDomと変数を管理する
    el:'#CompanyStationsVue',
    data: {
        // 会社の県に所属する路線一覧
        railroads: list['railroads'],
        // 会社に紐づく駅一覧
        companyStations: list['companyStations'],
        // 駅検索キーワード
        keyword: '',
        // 検索結果の駅一覧
        searchStations: [],
        // モダルの状態（駅追加のモダル）
        showModal: "",
        // 路線ごとの駅一覧
        stationModals: [],
        // 駅検索時のメッセージ
        message: "",
        /// 駅追加が可能かどうか
        enabled: true,
        // 検索可能かどうか（連続通信防止用）
        enabledSearch: true,
    },
    methods: {
        remove: function (index, station) {
            this.companyStations.splice(index, 1)
            this.setIfChangeEnable()
        },
        showStations: function (event) {
            var attributes = event.target.attributes
            var railroad = JSON.parse(attributes[0].nodeValue)
            var optionKey = parseInt(attributes[1].value)
            if (this.stationModals.indexOf(optionKey) !== -1) {
                this.stationModals.splice(index(this.stationModals, optionKey), 1)
            } else {
                this.stationModals.push(optionKey)
            }
        },
        getSearchStations: function() {
            if (!this.keyword.trim()) {
                return
            }
            var url = URL_BASE
            var params = {'keyword': this.keyword}
            // 連続送信防止
            this.enabledSearch = false
            return axios.post(url, params)
              .then((res) => {
                this.enabledSearch = true
                if (res.data.message) {
                    this.message = res.data.message
                } else {
                    this.message = ""
                }
                this.searchStations = res.data.stations
              })
              .catch((error) => {
                this.enabledSearch = true
                alert('取得に失敗しました。お手数ですが、時間を置いて再度やり直して下さい。')
              })
        },
        showstationSettingModal: function() {
            this.showModal = "stationSetting"
        },
        choiseAdd: function(event) {
            var station = JSON.parse(event.target.attributes[0].nodeValue)
            this.add(station)
        },
        add: function (station) {

            if (!this.notDeplicate(station, this.companyStations)) {
                alert('既に追加されています')
                return
            }
            var railroad = []
            if (!station.railroad) {
                railroad = this.railroads.filter(railroad => railroad.id == station.railroad_id)[0]
            } else {
                railroad = station.railroad
            }
            this.companyStations.push({ id: station.id, group_code: station.group_code, name:station.name, railroad: railroad })
            this.setIfChangeEnable()
            this.closeModal()
        },
        // 駅追加の可否を、登録個数によって判別
        setIfChangeEnable: function() {
            if ((this.companyStations).length >= 3) {
                this.enabled = false
            } else {
                this.enabled = true
            }
        },
        closeModal: function() {
            this.showModal = ''
            this.searchStations = []
            this.keyword = ''
            this.stationModals = []
            this.message = ""
        },
        notDeplicate: function(val, list) {
            var res = true
            list.forEach(function(row) {
                if (row.group_code == val.group_code) {
                    res = false
                    return false
                }
            })
            return res
        },
        checkeStationModal: function(key) {
            return this.stationModals.indexOf(key) !== -1
        }
    },
});

stationVue.setIfChangeEnable();



