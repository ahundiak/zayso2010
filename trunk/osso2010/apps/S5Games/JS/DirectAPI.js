Ext.ns('Zayso.Direct');

Zayso.Direct.API = function()
{
  var api = 
  {
    url       : 'index.php?direct',
    type      : 'remoting',
    namespace : 'Zayso.Direct',
    actions   : 
    {
      User_UserInfo:
      [
        { name: 'read',    len: 1 },
      ],
      User_UserSignIn:
      [
        { name: 'load',   len: 1 },
        { name: 'submit', len: 1, formHandler: true },
      ],
      Schedule:
      [
        { name: 'read',    len: 1 },
        { name: 'create',  len: 1 },
        { name: 'update',  len: 1 },
        { name: 'destroy', len: 1 },
      ],
      Event_EventPersonType: 
      [
        { name: 'read',    len: 1 },
      ],
      Referee_SignupCombo:
      [
        { name: 'read',    len: 1 },
      ]
    }
  };
  return api;
};
