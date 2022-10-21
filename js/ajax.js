function getFilters() {
    $.ajax({
        url: '/ajax/index.php',
        type: "POST",
        dataType: 'json',
        data: {
            request: 'getFilters',
            key: key,
        },
        success: function (jsondata) {
            if (jsondata.result == 'success') {
                var statuses = Object();
                for (var i in jsondata.data.status) {
                    var block = $('<option value="' + jsondata.data.status[i]['id'] + '">' + jsondata.data.status[i]['name'] + '</option>')
                    $('#state').append(block)
                    statuses[jsondata.data.status[i]['id']] = jsondata.data.status[i]['name'];
                }
                localStorage.setItem('key', jsondata.key);
                var statuses = JSON.stringify(statuses);
                localStorage.setItem("statuses", statuses);
            } else
                console.log(jsondata.errorMsg)
        },
        error: function (msg) {
            console.log(msg)
        }
    });
}

function getData(data) {
    console.log(data)
    var values = data.split('&')
    var res = Object();
    for (var i in values) {
        var vals = values[i].split('=')
        res[vals[0]] = vals[1];
    }
    var statuses = JSON.parse(localStorage.getItem("statuses"))
    $.ajax({
        url: '/ajax/index.php',
        type: "POST",
        dataType: 'json',
        data: {
            request: 'getData',
            key: key,
            data: res,
        },
        success: function (jsondata) {
            $('#declaration-list').find('tbody').empty()
            console.log(jsondata)

            if (jsondata.data.items.length > 0) {
                for (var i in jsondata.data.items) {
                    var bg_color = jsondata.colorStatuses[0];
                    if (jsondata.colorStatuses[jsondata.data.items[i]['idStatus']] !== undefined)
                        bg_color = jsondata.colorStatuses[jsondata.data.items[i]['idStatus']];

                    var tr = $('<tr>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['id'] + '</td>\
                              <td class="' + bg_color + '">' + statuses[jsondata.data.items[i]['idStatus']] + '</td>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['number'] + '</td>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['declDate'] + '</td>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['declEndDate'] + '</td>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['productFullName'] + '</td>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['applicantName'] + '</td>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['manufacterName'] + '</td>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['productOrig'] + '</td>\
                              <td class="' + bg_color + '">' + jsondata.data.items[i]['productSingleList'] + '</td>\
                              </tr>');
                    $('#declaration-list').children('tbody').append(tr)
                }

            }
            localStorage.setItem('key', jsondata.key);
        },
        error: function (msg) {
            console.log(msg)
        }
    });
}