<h1>Yii Cassandra CQL3 Wrapper</h1>

Provides object oriented access to Cassandra using CQL3 in a familiar Yii Style. This project is a wrapper over the famous phpcassa library.

This extension also handles issues with the phpcassa library 'Data Types' while using the latest CQL3 API provided by cassandra. The following discussion on StackOverflow describes the problem:

http://stackoverflow.com/questions/16139362/cassandra-is-not-retrieving-the-correct-integer-value

<h2>Usage</h2>

<h3>Configuring The Cassandra Connection</h3>
<p>Add this extension inside your extensions directory</p>
<p>Add the following to your application config</p>
<pre>
"components" => array(
	'cassandra' => array(
	'class' => 'ext.yii-cassandra-cql3.ACassandraConnection',
	'keyspace' => 'YOUR_KEYSPACE',
	'servers' => array('127.0.0.1')
	)
...
)
</pre>

<h3>Querying Cassandra using CQL3</h3>
<pre>
$query_result = Yii::app()->cassandra->cql3_query("Select * from users");
$rows = Yii::app()->cassandra->cql_get_rows($query_result);
print_r($rows);
die;
</pre>
<p>You can do other queries in similar fashion. For more info see the <a href="http://www.datastax.com/docs/1.2/cql_cli/querying_cql">cql3 reference</a></p>
