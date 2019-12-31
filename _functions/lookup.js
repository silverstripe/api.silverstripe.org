const APILookup = require('./APILookup');

exports.handler = function(event, _, callback) {
    const lookup = new APILookup(event.queryStringParameters);
    const versionMap = new Map();
    versionMap.set('master', '5');
    versionMap.set(/^(\d+)[.].*$/, '$1');
    
    const url = lookup.handle();

    callback(null, {
        statusCode: 302,
        headers: {
            "location": url,
        }
    });
}
