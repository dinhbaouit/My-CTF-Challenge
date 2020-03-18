import SocketServer

def o(a):
	secret = "0d020e101a535b44462f0a110b1d0b033605023708152f1d001a1d0d"
	secret = secret.decode("hex")
	key = "knowaboutpythonwillhelpyouopenthedoor"
	ret = ""
	for i in xrange(len(a)):
		ret += chr(ord(a[i])^ord(key[i%len(a)]))
	if ret == secret:
		print "Open the door"
	else:
		print "Close the door"


def x(a):
	xxx = "finding secret in o()"
	if len(a)>21:
		return "dai qua ..."
	return eval(a)

class MyTCPHandler(SocketServer.BaseRequestHandler):


    def handle(self):
    	self.request.sendall("This is function x()")
        self.request.sendall(">>> ")
        self.data = self.request.recv(1024).strip()
        print "{} wrote: {}".format(self.client_address[0],self.data)
        ret = x(str(self.data))
        
        self.request.sendall(str(ret))

if __name__ == "__main__":
    HOST, PORT = "0.0.0.0", 10002

    
    server = SocketServer.TCPServer((HOST, PORT), MyTCPHandler)


    server.serve_forever()