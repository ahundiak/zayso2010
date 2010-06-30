/* ===========================================
 * My overides to 3.1.0
 */
Ext.override(Ext.data.DirectProxy,
{
    /**
     * Callback for write actions
     * @param {String} action [{@link Ext.data.Api#actions create|read|update|destroy}]
     * @param {Object} trans  The request transaction object
     * @param {Object} result Data object picked out of the server-response.
     * @param {Object} res    The server response
     * @param {Ext.data.Record/[Ext.data.Record]} rs The Store resultset associated with the action.
     * @protected
     */
  
    onWrite : function(action, trans, result, res, rs) 
    {
      // console.log('DirectProxy.onWrite');
      // console.log(result);
      
        var data = trans.reader.extractData(result, false);
        
        // Only fire if success is true
        var success = result[trans.reader.meta.successProperty];
        if (success === true)
        {
          // console.log('Firing write event');
          this.fireEvent("write", this, action, data, res, rs, trans.request.arg);
        }
        else
        {
          /* Not convinced this is really an exception, should be success or failure events */
          this.fireEvent('exception', this, 'remote', action, trans, result, rs);
        }
        trans.request.callback.call(trans.request.scope, data, res, success);
    }
});

Ext.override(Ext.data.DataReader,
{

    extractData : function(root, returnRecords) {
    
        // FIX for create
        // console.log('DataReader.extractData ' + this.getRoot());
        // console.log(root);
        
        if (root.records) root = root.records;
        
        // A bit ugly this, too bad the Record's raw data couldn't be saved in a common property named "raw" or something.
        var rawName = (this instanceof Ext.data.JsonReader) ? 'json' : 'node';

        var rs = [];

        // Had to add Check for XmlReader, #isData returns true if root is an Xml-object.  Want to check in order to re-factor
        // #extractData into DataReader base, since the implementations are almost identical for JsonReader, XmlReader
        if (this.isData(root) && !(this instanceof Ext.data.XmlReader)) {
            root = [root];
        }
        var f       = this.recordType.prototype.fields,
            fi      = f.items,
            fl      = f.length,
            rs      = [];
        if (returnRecords === true) {
            var Record = this.recordType;
            for (var i = 0; i < root.length; i++) {
                var n = root[i];
                var record = new Record(this.extractValues(n, fi, fl), this.getId(n));
                record[rawName] = n;    // <-- There's implementation of ugly bit, setting the raw record-data.
                rs.push(record);
            }
        }
        else {
            for (var i = 0; i < root.length; i++) {
                var data = this.extractValues(root[i], fi, fl);
                data[this.meta.idProperty] = this.getId(root[i]);
                rs.push(data);
            }
        }
        return rs;
    }
});
