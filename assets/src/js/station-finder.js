/*eslint no-console: [0]*/
import 'intersection-observer';
import Vue from 'vue/dist/vue.esm.browser.min.js';
import {flatten, debounce, map, sortBy, union} from 'lodash-es';
import VLazyImage from "v-lazy-image";
/**
 * Station finder
 */
(function($, window, undefined) { // eslint-disable-line no-unused-vars

	var _ = {
		flatten: flatten,
		debounce: debounce,
		map: map,
		sortBy: sortBy,
		union: union
	};

	function getStates(states, selected_state, selected_city, selected_format) {
		if (selected_state !== 'all') {
			states = _.flatten(states.filter(function(state) {
				if (state.abbr === selected_state) {
					return state;
				}
			}));
		}
		if (selected_state !== 'all' && selected_city !== 'all') {
			states = _.flatten(states.filter(function(state) {
				if (state.cities.indexOf(selected_city) > -1) {
					return state;
				}
			}));
		}
		if (selected_format !== 'all') {
			states = _.flatten(states.filter(function(state) {
				if (state.formats.indexOf(selected_format) > -1) {
					return state;
				}
			}));
		}
		return states;
	}

	var finder = new Vue({ // eslint-disable-line no-unused-vars
		components: {
			VLazyImage
		},
		el: '#station-finder-widget',
		data: function() {
			return {
				stations: [],
				states: {},
				selected_state: 'all',
				selected_city: 'all',
				selected_format: 'all',
				query: ''
			}
		},
		template: `
			<div class="sf-stationfinder">
				<div class="sf-filters">
					<div class="sf-states">
						<label for="stations-states">
							Market State:
						</label>
						<select v-model="selected_state" id="stations-states">
							<option value="all">All</option>
							<option v-for="state in states" v-bind:value="state.abbr">{{state.name}}</option>
						</select>
					</div>
					<div class="sf-cities">
						<label for="stations-cities">
							Market City:
						</label>
						<select v-model="selected_city" id="stations-cities">
							<option value="all">All</option>
							<option v-for="city in filteredCities" v-bind:value="city">{{city}}</option>
						</select>
					</div>
					<div class="sf-formats">
						<label for="stations-formats">
							Formats:
						</label>
						<select v-model="selected_format" id="stations-formats">
							<option value="all">All</option>
							<option v-for="format in filteredFormats" v-bind:value="format">{{format}}</option>
						</select>
					</div>
					<div class="sf-search">
						<label for="stations-search">
							Search:
						</label>
						<input type="text" placeholder="Search..." v-on:keyup="debounceQuery" :value="query">
						<button v-on:click="query = ''">Clear</button>
					</div>
				</div>
				<div class="sf-stations">
					<a :href="station.url" class="sf-station" v-for="station in filteredStations">
						<figure>
							<v-lazy-image :src="station.image" src-placeholder="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" />
						</figure>
						<h3>{{station.id}}</h3>
						<div class="sf-details">{{station.freq}} {{station.band}} / {{station.format}}</div>
						<div class="sf-locale">{{station.city}}, {{station.state}}</div>
					</a>
				</div>
			</div>
		`,
		methods: {
			debounceQuery: _.debounce(function(e) {
				this.query = e.target.value;
			}, 200)
		},
		computed: {
			filteredStates: function() {
				var finder = this,
					states;
				
				states = getStates(finder.states, finder.selected_state, finder.selected_city, finder.selected_format);

				if (states.length === 1) {
					finder.selected_state = states[0].abbr;
				}
				return states;
			},
			filteredCities: function() {
				var finder = this,
					states, cities;

				states = getStates(finder.states, finder.selected_state, finder.selected_city, finder.selected_format);
				cities = _.flatten(_.map(states, 'cities'));

				if (cities.length === 1) {
					finder.selected_city = cities[0];
				}
				return cities;
			},
			filteredFormats: function() {
				var finder = this,
					states, formats;

				states = getStates(finder.states, finder.selected_state, finder.selected_city, finder.selected_format);
				formats = _.flatten(_.map(states, 'formats'));

				if (formats.length === 1) {
					finder.selected_format = formats[0];
				}
				return formats;
			},
			filteredStations: function() {
				var finder = this,
					stations;

				stations = this.stations.filter(function(station) {
					var ret = station;
					if (finder.query.length) {
						var searchable = [
							station.id,
							station.format,
							station.freq,
							station.band,
							station.city,
							station.state
						],
							found;
						searchable.forEach(function(value) {
							if (value.toLowerCase().indexOf(finder.query.toLowerCase()) > -1) {
								found = true;
							}
						});
						if ( ! found) {
							ret = null;
						}
					}
					if (finder.selected_state !== 'all' && station.state !== finder.selected_state) {
						ret = null;
					}
					if (finder.selected_city !== 'all' && station.city !== finder.selected_city) {
						ret = null;
					}
					if (finder.selected_format !== 'all' && station.format !== finder.selected_format) {
						ret = null;
					}
					if (ret) {
						return ret;
					}
				});

				_.sortBy(stations, ['city', 'state', 'id']);

				return stations;
			}
		},
		watch: {
			selected_state: function() {
				this.selected_city = 'all';
			}
		},
		created: function() {
			var finder = this;
			$.getJSON(
				'https://player.westwoodone.com/stations/stations.ashx',
				function(response) {
					finder.stations = response;

					var states = {};

					finder.stations.forEach(function(station) {
						if (states.hasOwnProperty(station.state)) {
							states[station.state].cities = _.union(states[station.state].cities, [station.city]);
							states[station.state].formats = _.union(states[station.state].formats, [station.format]);
							return;
						}
						states[station.state] = {
							abbr: station.state,
							name: stateNames[station.state],
							cities: [station.city],
							formats: [station.format]
						};
					});

					var sorted_states = _.sortBy(states, 'name');
					sorted_states.forEach(function(state) {
						state.cities = state.cities.sort();
						state.formats = state.formats.sort();
					});
					finder.states = sorted_states;
				}
			);
		}
	});

	var stateNames = { AZ: 'Arizona',
		AL: 'Alabama',
		AK: 'Alaska',
		AR: 'Arkansas',
		CA: 'California',
		CO: 'Colorado',
		CT: 'Connecticut',
		DC: 'District of Columbia',
		DE: 'Delaware',
		FL: 'Florida',
		GA: 'Georgia',
		HI: 'Hawaii',
		ID: 'Idaho',
		IL: 'Illinois',
		IN: 'Indiana',
		IA: 'Iowa',
		KS: 'Kansas',
		KY: 'Kentucky',
		LA: 'Louisiana',
		ME: 'Maine',
		MD: 'Maryland',
		MA: 'Massachusetts',
		MI: 'Michigan',
		MN: 'Minnesota',
		MS: 'Mississippi',
		MO: 'Missouri',
		MT: 'Montana',
		NE: 'Nebraska',
		NV: 'Nevada',
		NH: 'New Hampshire',
		NJ: 'New Jersey',
		NM: 'New Mexico',
		NY: 'New York',
		NC: 'North Carolina',
		ND: 'North Dakota',
		OH: 'Ohio',
		OK: 'Oklahoma',
		OR: 'Oregon',
		PA: 'Pennsylvania',
		RI: 'Rhode Island',
		SC: 'South Carolina',
		SD: 'South Dakota',
		TN: 'Tennessee',
		TX: 'Texas',
		UT: 'Utah',
		VT: 'Vermont',
		VA: 'Virginia',
		WA: 'Washington',
		WV: 'West Virginia',
		WI: 'Wisconsin',
		WY: 'Wyoming'
	};

}(jQuery, window.self));