pipeline {
    agent any

    environment {
        APP_IMAGE = "sihiy1/sasimga-app:latest"
        NGINX_IMAGE = "sihiy1/sasimga-nginx:latest"
    }

    stages {

        stage('Clone') {
            steps {
                git branch: 'main',
                url: 'https://github.com/TIM-UYE/sasimga-jember-presentasi.git'
            }
        }

        stage('Build App Image') {
            steps {
                sh 'docker build -t $APP_IMAGE -f dockerfile .'
            }
        }

        stage('Build Nginx Image') {
            steps {
                sh 'docker build -t $NGINX_IMAGE -f Dockerfile.nginx .'
            }
        }

        stage('Docker Login') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'dockerhub',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {

                    sh 'echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin'
                }
            }
        }

        stage('Push Images') {
            steps {
                sh 'docker push $APP_IMAGE'
                sh 'docker push $NGINX_IMAGE'
            }
        }

        stage('Deploy Swarm') {
            steps {
                sh 'docker stack deploy -c docker-stack.yml sasimga'
            }
        }
    }
}
