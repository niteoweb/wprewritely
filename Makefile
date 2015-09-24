VERSION := 2.0.1

test:
	bin/phpunit

release:
	cp -ar src wprewritely
	zip -r wprewritely.zip wprewritely
	rm -rf wprewritely
	mv wprewritely.zip build/

deploy:
	-bin/linux/amd64/github-release delete -u niteoweb -r wprewritely -t v$(VERSION)
	-bin/linux/amd64/github-release delete -u niteoweb -r wprewritely -t latest
	bin/linux/amd64/github-release release -u niteoweb -r wprewritely -t v$(VERSION)
	bin/linux/amd64/github-release release -u niteoweb -r wprewritely -t latest
	bin/linux/amd64/github-release upload -u niteoweb -r wprewritely -t v$(VERSION) -f build/wprewritely.zip -n wprewritely.zip
	bin/linux/amd64/github-release upload -u niteoweb -r wprewritely -t latest -f build/wprewritely.zip -n wprewritely.zip
