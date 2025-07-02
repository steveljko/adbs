IMAGE_NAME = localhost/adbs
CONTAINER_NAME = adbs

all: build run

clean:
	@echo "Stopping and removing container..."
	podman stop $(CONTAINER_NAME) || true
	podman rm $(CONTAINER_NAME) || true
	podman rmi $(IMAGE_NAME) || true

build: clean
	@echo "Building the image..."
	podman build -t $(IMAGE_NAME) .

run:
	@echo "Running the container..."
	podman run -d \
	  --name $(CONTAINER_NAME) \
	  -p 8011:80 \
	  $(IMAGE_NAME)

sh:
	@podman exec -it $(CONTAINER_NAME) bash

.PHONY: all clean build run
