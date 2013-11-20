help:
	@echo "test - run tests quickly with the default PHP"
	@echo "release - package and upload a release"

test:
	cd web/content/plugins/wprewritely
	php codecept.phar run --coverage

release: clean
	cp web/content/plugins/wprewritely wprewritely -ar
	rm -rf wprewritely/tests
	rm wprewritely/codecept.phar
	rm wprewritely/codeception.yml
	zip -r wprewritely-$(shell git describe --abbrev=0 --tags).zip wprewritely
	rm -rf wprewritely

clean:
	rm -rf wprewritely