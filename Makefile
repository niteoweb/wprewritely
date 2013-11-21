help:
	@echo "test - run tests quickly with the default PHP"
	@echo "release - package"
	@echo "publish - upload a release"

test:
	cd web/content/plugins/wprewritely; php codecept.phar run --coverage

release: clean
	cp web/content/plugins/wprewritely wprewritely -ar
	rm -rf wprewritely/tests
	rm wprewritely/codecept.phar
	rm wprewritely/codeception.yml
	zip -r wprewritely-$(shell git describe --abbrev=0 --tags).zip wprewritely
	rm -rf wprewritely

clean:
	rm -rf wprewritely

publish: 
	curl -X POST -d @wprewritely-$(shell git describe --abbrev=0 --tags).zip -i -H "Authorization: token TOKEN" -H "Accept: application/vnd.github.manifold-preview" "POST https://api.github.com//repos/niteoweb/wprewritely/releases"