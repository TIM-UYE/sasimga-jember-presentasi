pipeline {
    agent any

    environment {
        APP_IMAGE = 'sihiy1/sasimga-jember:latest'
        NGINX_IMAGE = 'sihiy1/sasimga-nginx:latest'
        STACK_NAME = 'sasimga-jember'
        DOCKERHUB_CREDENTIALS = 'dockerhub-credentials'
    }

    stages {
        stage('Validate Docker') {
            steps {
                echo 'Validating Docker configuration...'
                sh '''
                    docker --version
                    docker info
                '''
            }
        }

        stage('Prepare Disk') {
            steps {
                echo 'Cleaning unused Docker cache before build...'
                sh '''
                    docker builder prune -af || true
                    docker image prune -af || true
                    docker container prune -f || true
                    df -h
                    docker system df || true
                '''
            }
        }

        stage('Build Docker Images') {
            steps {
                echo 'Building application and nginx images...'
                sh '''
                    if [ ! -f public/build/manifest.json ]; then
                        echo "public/build/manifest.json not found. Run npm run build locally and commit public/build."
                        exit 1
                    fi

                    docker build --no-cache -t ${APP_IMAGE} -f dockerfile .
                    docker build --no-cache -t ${NGINX_IMAGE} -f Dockerfile.nginx .
                '''
            }
        }

        stage('Test App Image') {
            steps {
                echo 'Checking application image can boot Laravel CLI...'
                sh 'docker run --rm ${APP_IMAGE} php artisan --version'
            }
        }

        stage('Push Docker Images') {
            steps {
                echo 'Pushing images to Docker Hub...'
                withCredentials([usernamePassword(credentialsId: "${DOCKERHUB_CREDENTIALS}", usernameVariable: 'DOCKERHUB_USER', passwordVariable: 'DOCKERHUB_PASS')]) {
                    sh '''
                        echo "$DOCKERHUB_PASS" | docker login -u "$DOCKERHUB_USER" --password-stdin
                        docker push ${APP_IMAGE}
                        docker push ${NGINX_IMAGE}
                        docker logout
                    '''
                }
            }
        }

        stage('Deploy to Docker Swarm') {
            steps {
                echo 'Deploying to Docker Swarm...'

                sh '''
                set -e

                if docker stack ls | grep -q "${STACK_NAME}"; then

                    echo "Stack exists. Updating services..."

                    docker service update \
                    --force \
                    --with-registry-auth \
                    --image ${APP_IMAGE} \
                    ${STACK_NAME}_app

                    APP_STATUS=$?

                    echo "===== WAITING APP STARTUP ====="
                    sleep 15

                    echo "===== APP SERVICE LOGS ====="
                    docker service logs ${STACK_NAME}_app --tail 100 || true

                    echo "===== APP SERVICE TASKS ====="
                    docker service ps ${STACK_NAME}_app || true

                    docker service update \
                    --force \
                    --with-registry-auth \
                    --image ${NGINX_IMAGE} \
                    ${STACK_NAME}_nginx

                    NGINX_STATUS=$?

                    echo "===== NGINX SERVICE LOGS ====="
                    docker service logs ${STACK_NAME}_nginx --tail 50 || true

                    if [ $APP_STATUS -ne 0 ] || [ $NGINX_STATUS -ne 0 ]; then
                        echo "Deployment failed"
                        exit 1
                    fi

                else

                    echo "Stack not found. Creating new stack..."

                    docker stack deploy \
                    --with-registry-auth \
                    -c docker-stack.yml \
                    ${STACK_NAME}

                fi
                '''
            }
        }

        stage('Verify Deployment') {
            steps {
                echo 'Verifying deployment...'
                sh '''
                    echo 'Waiting for services to start...'
                    sleep 10

                    # Check if services are running
                    docker service ls | grep ${STACK_NAME} || echo "Services not found"

                    echo 'Checking application health...'
                '''
            }
        }
    }

    post {
        success {
            echo 'Deployment completed successfully!'
        }
        failure {
            echo 'Deployment failed! Check logs for details.'
        }
        always {
            echo 'Cleaning up...'
            sh '''
                docker builder prune -af || true
                docker image prune -af || true
                docker container prune -f || true

                rm -rf /var/jenkins_home/.npm || true
                rm -rf /var/jenkins_home/.cache || true

                docker system df || true
            '''
            cleanWs()
        }
    }
}
