import React from "react";
import Layout from "./components/Layout";
import { Button, Card } from "react-bootstrap";
import CourseList from "./components/CourseList";

const App = () => {
    return (
        <Layout>
            <Card>
                <Card.Header className="d-flex justify-content-between align-items-center">
                    Courses
                    <Button variant="primary" size="sm">
                        Create
                    </Button>
                </Card.Header>
                <Card.Body>
                    <Card.Text>
                        <CourseList />
                    </Card.Text>
                    <Button variant="primary">Go somewhere</Button>
                </Card.Body>
            </Card>
        </Layout>
    )
};

export default App;