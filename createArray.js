$(document).ready(function() {
    var request = $.ajax({
        type: "POST",
        url: "stock database",
        data: {"name":""}, 
        dataType: "html"
    });

    request.done(function(JSON_array) {
        array_data = JSON.parse(JSON_array)["array"]
       
    });
});

from flask import jsonify

@app.route("stock array")
def example():
    list = get_list()
    return jsonify(array=list)

@app.route("/")
def index():
     return render_template("portfolio.html")