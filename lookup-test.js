const { handler } = require('./_functions/lookup');

handler(
    {
        queryStringParameters: {
            q: 'SilverStripe\\ORM\\DataObject',
            version: '4',
        },
    },
    null,
    (_, obj) => {
        console.log(obj);
    }
);