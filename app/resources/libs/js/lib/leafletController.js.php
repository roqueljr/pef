<script>
const urlParams = new URLSearchParams(window.location.search);
const lat = urlParams.get("lat");
const lng = urlParams.get("lng");
const z = urlParams.get("z");


if (document.getElementById('map')) {

    const map = L.map("map");

    (() => {
        const x = lat ? parseFloat(lat) : 7.188806;
        const y = lng ? parseFloat(lng) : 125.300703;
        const zm = z ? parseFloat(z) : 12;

        map.setView([x, y], zm);
    })();

    // Base layers
    var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map); // Default layer

    var googleSat = L.tileLayer("https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}", {
        attribution: "&copy; Google"
    });

    var googleHybrid = L.tileLayer("https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}", {
        attribution: "&copy; Google"
    });

    var googleTerrain = L.tileLayer("https://mt1.google.com/vt/lyrs=p&x={x}&y={y}&z={z}", {
        attribution: "&copy; Google"
    });

    var TrueColor = L.tileLayer.wms(
        "https://sh.dataspace.copernicus.eu/ogc/wms/90338bf0-0deb-488d-9c2e-19e341f6dba7", {
            layers: "TRUE_COLOR",
            format: "image/png",
            transparent: true,
            attribution: "&copy; Sentinel Hub",
            minZoom: 13,
            maxZoom: 21,
            tileSize: 512,
            RESX: "10m",
            RESY: "10m",
            crs: L.CRS.EPSG3857,
            opacity: 1,
            version: "1.3.0",
            TIME: "2024-05-05",
            SHOWLEGEND: false
        });


    L.Control.geocoder({
            defaultMarkGeocode: false
        })
        .on('markgeocode', function(e) {
            var latlng = e.geocode.center;
            map.setView(latlng, 14);
            L.marker(latlng).addTo(map)
                .bindPopup(e.geocode.name)
                .openPopup();
        }).addTo(map);

    L.control.fullscreen().addTo(map);

    var baseMaps = {
        "OpenStreetMap": osm,
        "Google Satellite": googleSat,
        "Google Hybrid": googleHybrid,
        "Google Terrain": googleTerrain,
        "Sentinel-2 True Color (Latest)": TrueColor
    };

    L.control.layers(baseMaps).addTo(map);

    const featureLayersById = {};
    const checkboxToFeatureIds = {};

    const colorMap = {
        "Tambobong_Obu_Manuvu_AD": "#800080",
        "central_nursery": "#006600",
        "satellite_nursery": "#66ccff",
        "Ongoing": "#ffa500",
        "Done": "#00ff00",
        "parcel_maps": "#66ccff",
        "default": "#0055aa"
    };

    function mapView(data, checkboxId = null) {
        const currentFeatureIds = [];
        const allBounds = L.latLngBounds();
        let totalArea = 0;

        const label = document.querySelector(`label[for="${checkboxId}"]`);
        const span = label.querySelector('span');

        data.forEach(feature => {
            const featureId = feature.id;

            if (featureLayersById.hasOwnProperty(featureId)) {
                return;
            }

            const geojson = JSON.parse(feature.geometry);
            const properties = JSON.parse(feature.properties);
            const name = feature.name || properties.name || "Unnamed";
            const status = feature.status;
            let ptColor;

            switch (status) {
                case "Done":
                    ptColor = '#00FF00';
                    break;
                case "Ongoing":
                    ptColor = '#fae303';
                    break;
                case "Validated":
                    ptColor = '#fa03ab';
                    break;
                default:
                    ptColor = colorMap[name];
                    break;
            }

            const groupColor = ptColor;

            const featureLayer = L.geoJSON(geojson, {
                style: () => ({
                    fillColor: groupColor,
                    fillOpacity: 0.05,
                    color: groupColor,
                    weight: 2
                }),
                onEachFeature: (feature, layer) => {
                    let popupContent = "";
                    if (properties) {
                        Object.entries(properties).forEach(([key, value]) => {
                            if (key === 'hectarage') {
                                totalArea += parseFloat(value);
                            }

                            if (key !== 'fid' && key !== 'id') {
                                if (value !== null) {
                                    const label = key.charAt(0).toUpperCase() + key.slice(
                                        1);
                                    popupContent += `<b>${label}:</b> ${value}<br>`;
                                }
                            }
                        });
                        popupContent +=
                            `<a href="#" onclick="editmap(${featureId});">
                                More <i class="fa fa-caret-right"></i>
                             </a>`;
                    } else {
                        popupContent = "<b>No properties available</b>";
                    }
                    layer.bindPopup(popupContent);
                }
            }).addTo(map);

            const bounds = featureLayer.getBounds();
            if (bounds.isValid()) {
                allBounds.extend(bounds);
            }
            if (!featureLayersById.hasOwnProperty(featureId)) {
                featureLayersById[featureId] = featureLayer;
                currentFeatureIds.push(featureId);
            }
        });

        if (allBounds.isValid()) {
            map.fitBounds(allBounds);
            if (span) {
                span.innerText = ` [${totalArea.toFixed(3)}]`;
            }
        }

        if (checkboxId) {
            checkboxToFeatureIds[checkboxId] = currentFeatureIds;
        }
    }

    function editmap(id) {
        const getUrl = '/0/app/api/v1/map_editor.php?action=get&id=' + id;

        (async () => {
            const mapDetails = await mapController(getUrl);
            const labels = Object.keys(mapDetails[0]);
            const values = Object.values(mapDetails[0]);

            let tableRows = '';



            labels.forEach((item, index) => {
                if (index !== 2) {
                    tableRows += `
                        <tr>
                            <td style="width:100px">${item}</td>
                            <td>${values[index]}</td>
                        </tr>
                    `;
                }
            })

            const properties = JSON.parse(values[2]);
            const plabels = Object.keys(properties);
            const pvalues = Object.values(properties);

            tableRows += `
                    <tr class="success">
                        <th style="width:100px" colspan="2">Properties</th>
                    </tr>
                `;

            plabels.forEach((item, index) => {
                if (item !== 'fid' && item !== 'id') {
                    tableRows += `
                        <tr>
                            <td style="width:100px">${item}</td>
                            <td>${pvalues[index]}</td>
                        </tr>
                    `;
                }
            })

            const table = `
                <table class="table table-bordered table-striped" id="spTable">
                    <thead style="position:sticky;top:-18px">
                        <tr class="success">
                            <th style="width:100px" colspan="2">Spatial Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${tableRows}
                    </tbody>
                </table>
            `;

            const mId = 'aditMap';
            const title = `Map Details`;
            const body = table;
            const actions = `
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
            `;
            modal.crt(mId, title, body, actions);
            //<button class="btn btn-primary pull-left saveBtn">Save</button>
        })();
    }

    async function mapController(url) {
        try {
            const data = await fetch(url);
            if (!data.ok) throw new Error('Network is not ok!');
            const res = await data.json();
            if (!res.status) throw new Error(res.msg);
            return res.data;
        } catch (err) {
            console.error(err);
        }
    }

    async function getSpecificMap(mapdata, checkboxId = null) {
        mapdata.cntxt = 'spmap';
        //console.log(mapdata);
        try {
            const response = await fetch('/0/app/api/v1/map.php', {
                method: 'POST',
                body: JSON.stringify(mapdata)
            });

            if (!response.ok) throw new Error("Network is not ok!");

            const result = await response.json();

            if (!result.status) throw new Error(result.message);

            //console.log('data: ', result.data);

            if (Array.isArray(result.data)) {
                mapView(result.data, checkboxId);
            }
        } catch (err) {
            console.error('Error getting specific map: ', err);
            const featureIds = checkboxToFeatureIds[checkboxId];
            if (Array.isArray(featureIds)) {
                featureIds.forEach(fid => {
                    if (featureLayersById[fid]) {
                        map.removeLayer(featureLayersById[fid]);
                        delete featureLayersById[fid];
                    }
                });
                delete checkboxToFeatureIds[checkboxId];
            }
        }
    }

    const chkInputs = document.querySelectorAll('.form-check-input');
    const sMaps = {
        boundary: {
            ad: "Tambobong_Obu_Manuvu_AD"
        },
        nursery: {
            cn: "central_nursery",
            sn: "satellite_nursery"
        },
        planting: {
            op: "Ongoing",
            pt: "Done",
            sa: "Validated"
        },
        parcels: {
            p1: 49,
            p2: 50,
            p3: 51,
            p4: 52,
            p5: 53,
            p6: 54,
            p7: 55
        }
    };

    let data = {
        boundary: {},
        nursery: {},
        planting: {},
        parcels: {}
    };

    chkInputs.forEach(input => {
        input.addEventListener('click', function() {
            const id = input.id;
            const group = input.dataset.group;
            const isChecked = input.checked;
            const label = document.querySelector(`label[for="${this.id}"]`);
            const span = label.querySelector('span');

            if (isChecked) {
                data[group][id] = sMaps[group][id];

                const groupData = data[group];

                getSpecificMap(groupData, id);

            } else {
                delete data[group][id];
                if (span) {
                    span.innerText = '';
                }

                const featureIds = checkboxToFeatureIds[id];
                if (Array.isArray(featureIds)) {
                    featureIds.forEach(fid => {
                        if (featureLayersById[fid]) {
                            map.removeLayer(featureLayersById[fid]);
                            delete featureLayersById[fid];
                        }
                    });
                    delete checkboxToFeatureIds[id];
                }
            }
            //console.log('Active: ', checkboxToFeatureIds);
        });
    });
}
</script>