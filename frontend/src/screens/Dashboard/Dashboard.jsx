import React from 'react';
import Dropzone from 'react-dropzone'
import './Dashboard.css';
import axios from 'axios';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useHistory } from 'react-router-dom';

const Dashboard = () => {

    const history = useHistory();

    const onFileDrop = (file) => {
        const uploadFile = new FormData();
        uploadFile.append('file', file);
        toast("Uploading...");
        axios({
            method: 'post',
            url: 'http://localhost:80/api/import',
            data: uploadFile,
            headers: {'Content-Type': 'multipart/form-data' }
            })
            .then((response) => {
                toast.success("Success");
                setTimeout(() => {
                    history.push('/users');
                }, 2000);
                console.log(response);
            })
            .catch((response) => {
                toast.error("Error");
                console.log(response);
            });
    }

  return (
    <div className="container">
       <Dropzone onDrop={(acceptedFiles) => onFileDrop(acceptedFiles[0])}>
            {({getRootProps, getInputProps}) => (
                <section className="dropzone">
                <div {...getRootProps()}>
                    <input {...getInputProps()} />
                    <p>Drag 'n' drop some files here, or click to select files</p>
                </div>
                </section>
            )}
        </Dropzone>
        <ToastContainer />
    </div>
  );
}

export default Dashboard;
