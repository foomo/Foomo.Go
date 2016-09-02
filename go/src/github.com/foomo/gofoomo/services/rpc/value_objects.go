package rpc

// from php class Foomo\Services\RPC\Protocol\Call\MethodCall
// serializing a method call
type MethodCall struct {
	// id of the method call
	Id string `json:"id" bson:"id"`
	// name of the method to be called
	Method string `json:"method" bson:"method"`
	// the method call arguments
	Arguments []*struct {
		Name  string      `json:"name" bson:"name"`
		Value interface{} `json:"value" bson:"value"`
	} `json:"arguments" bson:"arguments"`
}

// from php class Foomo\Services\RPC\Protocol\Reply\MethodReply
// reply to a method call
type MethodReply struct {
	// id of the method call
	Id string `json:"id" bson:"id"`
	// return value
	Value interface{} `json:"value" bson:"value"`
	// server side exception
	Exception interface{} `json:"exception" bson:"exception"`
	// messages from the server
	// possibly many of them
	// possibly many types
	Messages interface{} `json:"messages" bson:"messages"`
}
