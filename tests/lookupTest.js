// Minimal, homespun testing framework to keep this project node dependency free.
// If the surface of the Javascript API ever increases, this should be replaced
// with a proper testing framework.

const { APILookup } = require('../_functions/lookup');

/**
 * https://github.com/moroshko/shallow-equal/blob/master/src/objects.js
 * @param object objA 
 * @param object objB 
 */
const objectCompare = (objA, objB) => {
    if (objA === objB) {
      return true;
    }
  
    if (!objA || !objB) {
      return false;
    }
  
    const aKeys = Object.keys(objA);
    const bKeys = Object.keys(objB);
    const len = aKeys.length;
  
    if (bKeys.length !== len) {
      return false;
    }
  
    for (let i = 0; i < len; i++) {
      const key = aKeys[i];
  
      if (objA[key] !== objB[key] || !Object.prototype.hasOwnProperty.call(objB, key)) {
        return false;
      }
    }
  
    return true;
};

/**
 * Test an expression
 * @param boolean exp 
 * @param string msg 
 */
const assert = (exp, msg = '') => {
    if (exp !== true) {
        console.error(`[FAIL] ${msg}`);
        
        return false;
    }

    console.log('Pass');
    return true;
};


const runTest = () => {
    let lookup;
    let results = [];
    let result;
    console.log('Testing getArgs()');
    lookup = new APILookup({ foo: 'bar' });
    result = assert(
        objectCompare({ foo: 'bar'}, lookup.getArgs()),
        `${JSON.stringify(lookup.getArgs())} is not expected { foo: 'bar' }`
    );
    results.push(result);

    console.log('Testing version regular expression');
    lookup = new APILookup({ version: 'foobarbaz'});
    lookup.setVersionMap(new Map([
        [/bar/, 'monkey']
    ]));
    result = assert('foomonkeybaz' === lookup.getVersion(), `${lookup.getVersion()} is not expected 'foomonkeybaz'`);
    results.push(result);

    console.log('Testing exact rule');
    lookup = new APILookup({ version: 'master'});
    lookup.setVersionMap(new Map([
        ['master', '5.x']
    ]));
    result = assert('5.x' === lookup.getVersion(), `${lookup.getVersion()} is not expected '5.x'`);
    results.push(result);

    console.log('Testing get version default');
    lookup = new APILookup({ version: 'unknown' });
    lookup.setVersionMap(new Map([
        ['master', '5.x'],
        ['4', '4.x'],
        ['3', '3.x'],
    ]));
    result = assert('unknown' === lookup.getVersion(), `${lookup.getVersion()} is not expected 'unknown'`);
    results.push(result);
    
    return results;
};

const results = runTest();
if (!results.every(r => r)) {
    process.exit(1);
}

process.exit(0);
